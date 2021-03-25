<?php declare(strict_types = 1);
/*
 * PoiXson phpUtils - Website Utilities Library
 * @copyright 2004-2021
 * @license GPL-3
 * @author lorenzo at poixson.com
 * @link https://poixson.com/
 */
namespace pxn\phpUtils\tests\exceptions;

use pxn\phpUtils\exceptions\NullPointerException as NullPointEx;
use pxn\phpUtils\exceptions\RequiredArgumentException;
use pxn\phpUtils\exceptions\FileNotFoundException as FileNotFoundEx;


class test_NumberUtils extends \PHPUnit\Framework\TestCase {



	/**
	 * @coversNothing
	 */
	public function test_native_exceptions(): void {
		$this->assertFalse(\class_exists('\\NullPointerException'));
		$this->assertFalse(\class_exists('\\FileNotFoundException'));
	}



	##################
	## Null Pointer ##
	##################



	/**
	 * @covers \pxn\phpUtils\exceptions\NullPointerException
	 */
	public function test_NullPointerException_without_arg(): void {
		$this->expectException(NullPointEx::class);
		$this->expectExceptionMessage('');
		throw new NullPointEx();
	}

	/**
	 * @covers \pxn\phpUtils\exceptions\NullPointerException
	 */
	public function test_NullPointerException_with_arg(): void {
		$this->expectException(NullPointEx::class);
		$this->expectExceptionMessage('Null Pointer Exception: test');
		throw new NullPointEx('test');
	}



	#######################
	## Required Argument ##
	#######################



	/**
	 * @covers \pxn\phpUtils\exceptions\RequiredArgumentException
	 */
	public function test_RequiredArgumentException_without_arg(): void {
		$this->expectException(RequiredArgumentException::class);
		$this->expectExceptionMessage('');
		throw new RequiredArgumentException();
	}

	/**
	 * @covers \pxn\phpUtils\exceptions\RequiredArgumentException
	 */
	public function test_RequiredArgumentException_with_arg(): void {
		$this->expectException(RequiredArgumentException::class);
		$this->expectExceptionMessage('Required Argument: test');
		throw new RequiredArgumentException('test');
	}



	####################
	## File Not Found ##
	####################



	/**
	 * @covers \pxn\phpUtils\exceptions\FileNotFoundException
	 */
	public function test_FileNotFoundException_without_arg(): void {
		$this->expectException(FileNotFoundEx::class);
		$this->expectExceptionMessage('');
		throw new FileNotFoundEx();
	}

	/**
	 * @covers \pxn\phpUtils\exceptions\FileNotFoundException
	 */
	public function test_FileNotFoundException_with_arg(): void {
		$this->expectException(FileNotFoundEx::class);
		$this->expectExceptionMessage('File not found: test');
		throw new FileNotFoundEx('test');
	}



}
