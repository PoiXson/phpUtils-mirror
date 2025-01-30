<?php declare(strict_types=1);
/*
 * PoiXson phpUtils - PHP Utilities Library
 * @copyright 2004-2025
 * @license AGPLv3+ADD-PXN-V1
 * @author lorenzo at poixson.com
 * @link https://poixson.com/
 */
namespace pxn\phpUtils\tools;

use \pxn\phpUtils\utils\ShellUtils;


class ShellHelp {

	const HELP_WIDTH       = 80;
	const MIN_FLAG_SPACING = 11;

	protected ?string $name = null;
	protected ?string $msg  = null;
	protected array $appendUsage = [];

	protected ?string $command  = null;
	protected array   $commands = [];

	protected array $flags = [
		'pre' => [],
		'mid' => [],
		'pst' => []
	];



	public function __construct(?string $command=null) {
		$this->setCommand($command);
	}



	public function setSelfName(?string $name): self {
		$this->name = (empty($name) ? null : (string) $name);
		return $this;
	}
	public function appendSelfName(string $name): void {
		$this->name = \implode([ $this->name, $name ],' ');
	}
	public function getSelfName(): string {
		if (empty($this->name))
			return \basename($_SERVER['PHP_SELF']);
		return (string) $this->name;
	}



	public function setMessage(?string $msg=null): self {
		if (empty($msg))          $this->msg = null;
		else if (\is_array($msg)) $this->msg = \implode("\n", $msg);
		else                      $this->msg = $msg;
		return $this;
	}



	public function appendUsage(string $msg): void {
		if (\is_array($msg)) {
			$this->appendUsage = \array_merge($this->appendUsage, $msg);
		} else
		if (!empty($msg)) {
			$this->appendUsage[] = $msg;
		}
	}



	public function setCommand(string $command): self {
		$this->command = (empty($command) ? null : (string) $command);
		return $this;
	}
	public function addCommands(array $commands): self {
		$this->commands = \array_merge($this->commands, $commands);
		return $this;
	}



	public function addFlags(array $flags, ?string $position=null): self {
		$position = \mb_strtolower($position);
		if ($position == 'post')
			$position = 'pst';
		if (isset($this->flags[$position])) {
			$this->flags[$position] = \array_merge($this->flags[$position], $flags);
		} else {
			$this->flags[$position] = $flags;
		}
		return $this;
	}



	public function Display(): void {
		echo "\n";
		$this->Display_Usage();
		$this->Display_Commands();
		$this->Display_Flags();
	}
	public function Display_Usage(): void {
		$usage = [];
		$usage[] = $this->getSelfName();
		if (empty($this->command)) {
			if (!\is_array($this->commands) || \count($this->commands) > 0)
				$usage[] = '<command>';
		} else {
			$usage[] = (string) $this->command;
		}
		$usage[] = '[flags]';
		if (\is_array($this->appendUsage) && \count($this->appendUsage) > 0)
			$usage = \array_merge($usage, $this->appendUsage);
		$usageStr = \implode($usage, ' ');
		unset($usage);
		if (!empty($this->msg)) {
			$msg = Strings::Trim($this->msg);
			$lines = Strings::WrapLines($msg, self::HELP_WIDTH);
			foreach ($lines as $line)
				echo "{$line}\n";
		}
		echo ShellUtils::FormatString(
			"{color=orange}Usage:{reset}\n".
			"  {$usageStr}\n"
		);
		echo "\n";
	}
	public function Display_Commands(): void {
		if (!\is_array($this->commands)
		|| \count($this->commands) == 0)
			return;
		echo ShellUtils::FormatString(
			"{color=orange}Commands:{reset}\n"
		);
		// find max command length
		$maxSize = 0;
		foreach ($this->commands as $command => $desc) {
			$len = \mb_strlen($command);
			if ($len > $maxSize)
				$maxSize = $len;
		}
		$maxSize += 3;
		// display commands
		foreach ($this->commands as $command => $desc) {
			$padding = \str_repeat(' ', $maxSize - \mb_strlen($command) );
			$lines = Strings::WrapLines($desc, self::HELP_WIDTH - $maxSize);
			$firstLine = $lines[0];
			echo ShellUtils::FormatString(
				"  {color=green}$command{reset}$padding{$firstLine}\n"
			);
			// multi-line description
			if (\count($lines) > 1) {
				unset($lines[0]);
				foreach ($lines as $line) {
					$padding = \str_repeat(' ', $maxSize + 2);
					echo "{$padding}{$line}\n";
				}
			}
		}
		echo "\n";
	}
	public function Display_Flags(): void {
		echo ShellUtils::FormatString(
			"{color=orange}Flags:{reset}\n"
		);
		$firstGroup = (isset($this->flags['pre']) ? $this->flags['pre'] : []);
		$lastGroup  = (isset($this->flags['pst']) ? $this->flags['pst'] : []);
		// display flag groups
		$this->Display_FlagGroup('pre', $firstGroup);
		foreach ($this->flags as $group => $flags) {
			if ($group == 'pre' || $group == 'pst')
				continue;
			$this->Display_FlagGroup($group, $flags);
		}
		$this->Display_FlagGroup('pst', $lastGroup);
	}
	public function Display_FlagGroup(string $group, array $flags): void {
		if (!\is_array($flags) || \count($flags) == 0)
			return;
		$prepared = [];
		$maxSize = 0;
		foreach ($flags as $desc => $entries) {
			// prepare flag strings
			$singles = [];
			$doubles = [];
			foreach ($entries as $entry) {
				$str = Strings::TrimFront($entry, '-');
				if (empty($str))
					continue;
				if (\mb_strlen($str) == 1) {
					$singles[] = Strings::ForceStartsWith($entry, '-');
				} else {
					while(!\str_starts_with(haystack: $entry, needle: '--'))
						$entry = "-{$entry}";
					$doubles[] = $entry;
				}
			}
			// prepare string "-f, --flag"
			$flagStr = \implode(\array_merge($singles, $doubles), ', ');
			// find max length
			$len = \mb_strlen($flagStr);
			if ($len > $maxSize)
				$maxSize = $len;
			$prepared[$desc] = $flagStr;
		}
		if ($maxSize < self::MIN_FLAG_SPACING)
			$maxSize = self::MIN_FLAG_SPACING;
		$maxSize += 3;
		// display prepared group of flags
		foreach ($prepared as $desc => $flagStr) {
			$padding = \str_repeat(' ', $maxSize - \mb_strlen($flagStr) );
			$lines = Strings::WrapLines($desc, self::HELP_WIDTH - $maxSize);
			$firstLine = $lines[0];
			echo ShellUtils::FormatString(
				"  {color=green}$flagStr{reset}$padding{$firstLine}\n"
			);
			// multi-line description
			if (\count($lines) > 1) {
				unset($lines[0]);
				foreach ($lines as $line) {
					$padding = \str_repeat(' ', $maxSize + 2);
					echo "{$padding}{$line}\n";
				}
			}
		}
		echo "\n";
	}



}
