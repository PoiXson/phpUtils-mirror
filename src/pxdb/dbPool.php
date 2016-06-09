<?php
/*
 * PoiXson phpUtils - PHP Utilities Library
 * @copyright 2004-2016
 * @license GPL-3
 * @author lorenzo at poixson.com
 * @link http://poixson.com/
 */
namespace pxn\phpUtils\pxdb;

use pxn\phpUtils\Strings;
use pxn\phpUtils\San;


class dbPool {

	const dbNameDefault = 'main';
	const MaxConnections = 5;  // max connections per pool

	// pools[name]
	protected static $pools = [];

	protected $dbName = NULL;
	// conns[index]
	protected $conns   = [];

	protected $usingTables = [];
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



	public static function getPoolName($pool=NULL) {
		$p = dbPool::getPool($pool);
		if ($p == NULL) {
			return NULL;
		}
		return $p->getName();
	}
	public function getName() {
		return $this->dbName;
	}



	public function getConnCount() {
		return \count($this->conns);
	}



	##########################
	## table / field exists ##
	##########################



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
		$db->Execute("SHOW TABLES");
		$database = $db->getDatabaseName();
		while ($db->hasNext()) {
			$tableName = $db->getString("Tables_in_{$database}");
			if (Strings::StartsWith($tableName, '_'))
				continue;
			$this->knownTables[] = $tableName;
		}
		$db->release();
		return $this->knownTables;
	}
	public function hasTable($tableName) {
		$tableName = San::AlphaNumUnderscore($tableName);
		if (empty($tableName)) {
			return NULL;
		}
		return \in_array(
			$tableName,
			$this->getKnownTables()
		);
	}



	#####################
	## Update Schema's ##
	#####################



	public function UsingTables(...$tables) {
		$this->usingTables = \array_merge($this->usingTables, $tables);
	}
	public function getUsedTables() {
		return $this->usingTables;
	}



	// update table schemas
	public function UpdateTables($tables=NULL) {
		// default to all tables
		if (empty($tables)) {
			$tables = $this->getUsedTables();
		}
		// array of table names
		if (\is_array($tables)) {
			foreach ($tables as $tableName) {
				if ($tableName == NULL || empty($tableName)) {
					continue;
				}
				if (Strings::StartsWith($tableName, '_'))
					continue;
				if (!$this->doUpdateTable($tableName)) {
					return FALSE;
				}
			}
			return TRUE;
		}
		// single table name
		return $this->doUpdateTable($tables);
	}
	protected function doUpdateTable($tableName) {
		$tableName = San::AlphaNumUnderscore($tableName);
		if (empty($tableName)) {
			fail('Table argument is required!');
			exit(1);
		}
		if (Strings::StartsWith($tableName, '_')) {
			fail("Cannot use tables starting with an underscore: {$tableName}");
			exit(1);
		}
		// find table schema
		$namespace = NULL;
		if (\class_exists('pxn\\phpPortal\\Website')) {
			$namespace = \pxn\phpPortal\Website::getSiteNamespace();
		}
		if (empty($namespace)) {
			fail('Failed to find project namespace!');
			exit(1);
		}
		$clss = "{$namespace}\\schemas\\table_{$tableName}";
		if (!\class_exists($clss)) {
			fail("db table schema class not found: {$clss}");
			exit(1);
		}
		$schema = new $clss();
		$fields = $schema->getFields();

		// create new table
		if (!$this->hasTable($tableName)) {
			// get first field
			\reset($fields);
			list($fieldName, $field) = \each($fields);
			$field['name'] = $fieldName;
			$this->CreateTable(
				$tableName,
				$field
			);
		}

		// check fields
//TODO:





		return TRUE;
	}
	public function CreateTable($tableName, $firstField) {
		$tableName = San::AlphaNumUnderscore($tableName);
		if (empty($tableName)) {
			fail('table name argument is required!');
			exit(1);
		}
		if (Strings::StartsWith($tableName, '_')) {
			fail("Cannot create tables starting with underscore: {$tableName}");
			exit(1);
		}
		if ($this->hasTable($tableName)) {
			fail("Cannot create table, already exists: {$tableName}");
			exit(1);
		}
		if (empty($firstField)) {
			fail('first field argument is required!');
			exit(1);
		}
		$db = $this->getDB();
		// create table sql
		$fieldSQL = self::getFieldSQL($firstField);
		$engine = 'InnoDB';
		$sql = "CREATE TABLE `{$tableName}` ( {$fieldSQL} ) ENGINE={$engine} DEFAULT CHARSET=latin1";
		$db->Execute($sql);
		if (\strtolower($firstField['type']) == 'increment') {
			$fieldName = $firstField['name'];
			if (!self::InitAutoIncrementField($db, $tableName, $fieldName)) {
				fail("Failed to finish creating auto increment field: {$fieldName}");
				exit(1);
			}
		}
		$this->knownTables[] = $tableName;
		$db->release();
	}



	protected static function getFieldSQL($field) {
		if (!isset($field['name']) || empty($field['name'])) {
			fail('Field name is required!');
			exit(1);
		}
		$name = San::AlphaNumUnderscore( $field['name'] );
		if (Strings::StartsWith($name, '_')) {
			fail("Field names cannot start with underscore: {$name}");
			exit(1);
		}
		if (!isset($field['type']) || empty($field['type'])) {
			fail('Field type is required!');
			exit(1);
		}
		$type = San::AlphaNumUnderscore( $field['type'] );
		$sql = [];
		// name
		$sql[] = "`{$name}`";
		// type
		// auto increment
		if (\strtolower($type) == 'increment') {
			$sql[] = 'int(11)';
			$field['nullable'] = FALSE;
		} else {
			$sql[] = $type;
			if (isset($field['size']) && !empty($field['size'])) {
				$size = San::AlphaNumSpaces($field['size']);
				$sql[] = "({$size})";
			}
		}
		// null / not null
		if (!isset($field['nullable'])) {
			switch (\strtolower($type)) {
			case 'int':
			case 'tinyint':
			case 'smallint':
			case 'mediumint':
			case 'bigint':
			case 'decimal':
			case 'float':
			case 'double':
			case 'bit':
			case 'boolean':
				$field['nullable'] = FALSE;
				break;
			case 'varchar':
			case 'char':
			case 'text':
			case 'longtext':
			case 'blob':
				$field['nullable'] = TRUE;
				break;
			case 'date':
			case 'time':
			case 'datetime':
				$field['nullable'] = FALSE;
				break;
			case 'enum':
			case 'set':
				$field['nullable'] = TRUE;
				break;
			default:
				$field['nullable'] = TRUE;
				break;
			}
		}
		$sql[] = ($field['nullable'] == FALSE ? 'NOT ' : '').'NULL';
		// done
		return \implode(' ', $sql);
	}
	protected static function InitAutoIncrementField($db, $tableName, $fieldName) {
		$tableName = San::AlphaNumUnderscore($tableName);
		$fieldName = San::AlphaNumUnderscore($fieldName);
		$sql = "ALTER TABLE `{$tableName}` ADD PRIMARY KEY ( `{$fieldName}` )";
		if (!$db->Execute($sql)) {
			return FALSE;
		}
		$sql = "ALTER TABLE `{$tableName}` MODIFY `{$fieldName}` int(11) NOT NULL AUTO_INCREMENT";
		if (!$db->Execute($sql)) {
			return FALSE;
		}
		return TRUE;
	}



}
