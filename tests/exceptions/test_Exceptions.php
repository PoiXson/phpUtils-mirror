<?php declare(strict_types = 1);
/*
 * PoiXson phpUtils - Website Utilities Library
 * @copyright 2004-2021
 * @license GPL-3
 * @author lorenzo at poixson.com
 * @link https://poixson.com/
 */
namespace pxn\phpUtils\tests\exceptions;

use pxn\phpUtils\exceptions\NullPointerException;
use pxn\phpUtils\exceptions\RequiredArgumentException;
use pxn\phpUtils\exceptions\FileNotFoundException;


class test_NumberUtils extends \PHPUnit\Framework\TestCase {



	/**
	 * @covers \pxn\phpUtils\exceptions\NullPointerException
	 */
	public function test_NullPointerException_without_arg(): void {
		$this->expectException(NullPointerException::class);
		$this->expectExceptionMessage('');
		throw new NullPointerException();
	}
	/**
	 * @covers \pxn\phpUtils\exceptions\NullPointerException
	 */
	public function test_NullPointerException_with_arg(): void {
		$this->expectException(NullPointerException::class);
		$this->expectExceptionMessage('Null Pointer Exception: test');
		throw new NullPointerException('test');
	}



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



	/**
	 * @covers \pxn\phpUtils\exceptions\FileNotFoundException
	 */
	public function test_FileNotFoundException_without_arg(): void {
		$this->expectException(FileNotFoundException::class);
		$this->expectExceptionMessage('');
		throw new FileNotFoundException();
	}
	/**
	 * @covers \pxn\phpUtils\exceptions\FileNotFoundException
	 */
	public function test_FileNotFoundException_with_arg(): void {
		$this->expectException(FileNotFoundException::class);
		$this->expectExceptionMessage('File not found: test');
		throw new FileNotFoundException('test');
	}



}
