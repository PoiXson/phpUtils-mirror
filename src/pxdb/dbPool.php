<?php
/*
 * PoiXson phpUtils - PHP Utilities Library
 * @copyright 2004-2016
 * @license GPL-3
 * @author lorenzo at poixson.com
 * @link http://poixson.com/
 */
namespace pxn\phpUtils\pxdb;


class dbPool {

	const dbNameDefault = 'main';
	const MaxConnections = 5;  // max connections per pool

	// pools[name]
	protected static $pools = array();

	protected $dbName = NULL;
	// conns[index]
	protected $conns   = array();

	protected $knownTables = NULL;



	public static function configure(
		$dbName,
		$driver,
		$host,
		$port,
		$u,
		$p,
		$database,
		$prefix
	) {
		$conn = dbConn::Factory(
			(string) $dbName,
			(string) $driver,
			(string) $host,
			(int)    $port,
			(string) $u,
			(string) $p,
			(string) $database,
			(string) $prefix
		);
		unset($u, $p);
		$db = new self(
			$dbName,
			$conn
		);
		self::$pools[$dbName] = $db;
	}
	public function __construct($dbName, $conn) {
		$this->dbName = $dbName;
		$this->conns[] = $conn;
	}



	public static function get($dbName=NULL, $errorMode=dbConn::ERROR_MODE_EXCEPTION) {
		$pool = self::getPool($dbName);
		if ($pool == NULL) {
			return NULL;
		}
		$db = $pool->getDB();
		$db->setErrorMode($errorMode);
		return $db;
	}
	public static function getPool($dbName=NULL) {
		// already pool instance
		if ($dbName != NULL && $dbName instanceof dbPool) {
			return $dbName;
		}
		// default db
		if (empty($dbName)) {
			$dbName = self::dbNameDefault;
		}
		$dbName = (string) $dbName;
		// db pool doesn't exist
		if (!self::dbExists($dbName)) {
			fail("Database isn't configured: $dbName");
			exit(1);
		}
		return self::$pools[$dbName];
	}
	public function getDB() {
		// get db connection
		$found = NULL;
		// find unused
		foreach ($this->conns as $conn) {
			// connection in use
			if ($conn->inUse())
				continue;
			// available connection
			$found = $conn;
			break;
		}
		// clone if in use
		if ($found === NULL) {
			if (count($this->conns) >= self::MaxConnections) {
				fail("Max connections reached for database: {$dbName}");
				exit(1);
			}
			// get first connection
			$conn = \reset($this->conns);
			// clone the connection
			$found = $conn->cloneConn();
		}
		$found->lock();
		$found->clean();
		return $found;
	}



	public static function dbExists($dbName=NULL) {
		if (empty($dbName)) {
			$dbName = self::$dbNameDefault;
		}
		return isset(self::$pools[$dbName])
			&& self::$pools[$dbName] != NULL;
	}



	public function getName() {
		return $this->dbName;
	}



	public function getConnCount() {
		return \count($this->conns);
	}



	public function getKnownTables() {
		// cached table list
		if ($this->knownTables != NULL) {
			return $this->knownTables;
		}
		// get known tables
		$db = $this->getDB();
		if ($db == NULL) {
			fail('Failed to get db for list of tables!');
			exit(1);
		}
		$db->Prepare("SHOW TABLES");
		$db->Execute();
		$database = $db->getDatabaseName();
		$tables = [];
		while ($db->hasNext()) {
			$tables[] = $db->getString("Tables_in_{$database}");
		}
		self::$knownTables[$poolName] = $tables;
		$db->release();
		return self::$knownTables[$poolName];
	}
	public function hasTable($table) {
		$table = (string) $table;
		if (empty($table)) {
			return NULL;
		}
		return \in_array(
			$table,
			$this->getKnownTables()
		);
	}



}
