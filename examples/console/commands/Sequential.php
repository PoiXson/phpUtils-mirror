<?php declare(strict_types=1);
/*
 * PoiXson phpUtils - Website Utilities Library
 * @copyright 2004-2025
 * @license AGPLv3+ADD-PXN-V1
 * @author lorenzo at poixson.com
 * @link https://poixson.com/
 * /
namespace pxn\phpUtils\examples\console\commands;

use \Symfony\Component\Console\Input\InputInterface;
use \Symfony\Component\Console\Output\OutputInterface;


class Sequential extends \pxn\phpUtils\console\Command {

	private static $instance = null;



	public static function get() {
		if (self::$instance == null) {
			$command = self::RegisterNew('sequential');
			$command->setAliases(['seq']);
			$command->setInfo(
					'Example command displays ramdom numbers',
					'HELP!',
					'USAGE?'
			);
			self::$instance = $command;
		}
		return self::$instance;
	}



	protected function execute(InputInterface $input, OutputInterface $output) {
		echo "\n\n";
		echo "Running Command: SEQ\n";
		for ($i=1; $i<=5; $i++) {
			echo '  '.$i;
		}
		echo "\n\n";
	}



}
*/
