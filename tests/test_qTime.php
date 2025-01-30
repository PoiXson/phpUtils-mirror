<?php declare(strict_types=1);
/*
 * PoiXson phpUtils - Website Utilities Library
 * @copyright 2004-2025
 * @license AGPLv3+ADD-PXN-V1
 * @author lorenzo at poixson.com
 * @link https://poixson.com/
 */
namespace pxn\phpUtils\tests;

use \pxn\phpUtils\qTime;
use \pxn\phpUtils\utils\GeneralUtils;


/**
 * @coversDefaultClass \pxn\phpUtils\qTime
 */
class test_qTime extends \PHPUnit\Framework\TestCase {



	/**
	 * @covers ::getTimeSinceStart
	 * @covers ::getTimeSinceLast
	 * @covers ::getGlobalSinceLast
	 * @covers \pxn\phpUtils\utils\GeneralUtils::Sleep
	 */
	public function test_Short() {
		// times are in seconds
		$this->execute(0.010);
	}
	/**
	 * @covers ::getTimeSinceStart
	 * @covers ::getTimeSinceLast
	 * @covers ::getGlobalSinceLast
	 * @covers \pxn\phpUtils\utils\GeneralUtils::Sleep
	 */
	public function test_Longer() {
		// times are in seconds
		$this->execute(0.500);
	}
	private function execute($sleepTime) {
		// test global
		qTime::getGlobal()->Reset();
		$g = qTime::getGlobalSinceStart();
		$this->assertGreaterThanOrEqual(0.0, $g);
		$this->assertLessThan(          0.1, $g);
		unset($g);
		// test local instance
		$timer = new qTime(true);
		$a = $timer->getTimeSinceStart();
		// wait a short while
		GeneralUtils::Sleep( (int) ($sleepTime*1000.0) );
		$b = $timer->getTimeSinceLast();
		// wait a short while again
		GeneralUtils::Sleep( (int) ($sleepTime*1000.0) );
		$c = $timer->getTimeSinceLast();
		// test time to start
		$this->assertGreaterThanOrEqual(0.0, $a);
		$this->assertLessThan(          0.1, $a);
		// test 1x sleep
		$this->assertGreaterThan($sleepTime * 0.9, $b);
		$this->assertLessThan(   $sleepTime * 1.9, $b);
		// test 2x sleep
		$this->assertGreaterThan($sleepTime * 0.9, $c);
		$this->assertLessThan(   $sleepTime * 1.9, $c);
		// test deviation
		$deviation = \abs($c - $b);
		$this->assertGreaterThanOrEqual(0.0, $deviation);
		$this->assertLessThan(          0.1, $deviation);
		// test overall time
		$finish = $timer->getTimeSinceStart();
		$this->assertGreaterThan($sleepTime *  1.9, $finish);
		$this->assertLessThan(   $sleepTime * 10.0, $finish);
		// test global
		qTime::getGlobalSinceLast();
		unset($timer, $a, $b, $c);
	}



}
