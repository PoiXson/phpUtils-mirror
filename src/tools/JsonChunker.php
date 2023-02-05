<?php declare(strict_types=1);
/*
 * PoiXson phpUtils - PHP Utilities Library
 * @copyright 2004-2023
 * @license AGPL-3
 * @author lorenzo at poixson.com
 * @link https://poixson.com/
 */
namespace pxn\phpUtils\tools;


class JsonChunker {

	protected String $buffer = '';

	protected bool $insideSingleQuote = false;
	protected bool $insideDoubleQuote = false;
	protected int  $insideBrackets = 0;

	protected JsonChunkProcessor $processor;



	public function __construct(JsonChunkProcessor $processor) {
		$this->processor = $processor;
	}



	public function process_string(String $data): void {
		$len = \strlen($data);
		for ($i=0; $i<$len; $i++) {
			$this->process_char(\mb_substr($data, $i, 1));
		}
	}
	public function process_char(String $chr): void {
		if ($chr == "\r") return;
		if (empty($this->buffer)) {
			switch ($chr) {
			case '{': break;
			case "\n": case ',':
			case "\t": case ' ': return;
			default:
				throw new \RuntimeException(
					\sprintf(
						'JSON must start with { bracket, found: %s <%d>',
						$chr,
						\ord($chr)
					)
				);
			}
		}
		switch ($chr) {
		case '{': {
			if ($this->insideSingleQuote) break;
			if ($this->insideDoubleQuote) break;
			$this->insideBrackets++;
			break;
		}
		case '}': {
			if ($this->insideSingleQuote) break;
			if ($this->insideDoubleQuote) break;
			$this->insideBrackets--;
			if ($this->insideBrackets == 0) {
				$this->buffer .= '}';
				$this->processor->process($this->buffer);
				$this->buffer = '';
				return;
			} else
			if ($this->insideBrackets < 0) {
				throw new \RuntimeException("Invalid brackets in json");
			}
			break;
		}
		case "'": {
			if ($this->insideDoubleQuote) break;
			$this->insideSingleQuote = !$this->insideSingleQuote;
			break;
		}
		case '"': {
			if ($this->insideSingleQuote) break;
			$this->insideDoubleQuote = !$this->insideDoubleQuote;
			break;
		}
		default: break;
		}
		$this->buffer .= $chr;
	}



}
