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


class Random extends \pxn\phpUtils\console\Command {

	private static $instance = null;



	public static function get() {
		if(self::$instance == null) {
			$command = self::RegisterNew('random');
			$command->setAliases(['rand']);
			$command->setInfo(
				'Example command displays ramdom numbers',
				'HELP!',
				'USAGE?'
			);
			self::$instance = $command;
		}
		return self::$instance;
	}

//		$this
//			->setDefinition([
//				new InputArgument(
//						'command_name',
//						InputArgument::OPTIONAL,
//						'The command name',
//						'help'
//				),
//				new InputOption(
//						'format',
//						null,
//						InputOption::VALUE_REQUIRED,
//						'The output format (txt, xml, json, or md)',
//						'txt'
//				),
//			]
//		)
//		->setHelp(<<<EOF
//The <info>%command.name%</info> example command displays ramdom numbers:
//
//  <info>php %command.full_name%</info>
//EOF
//		);
//	}



	protected function execute(InputInterface $input, OutputInterface $output) {
		echo "\n\n";
		echo "Running Command: RANDOM\n";
		for ($i=1; $i<=5; $i++) {
			echo '  '.\mt_rand(0, 9);
		}
		echo "\n\n";

//		if($this->command == null) {
//			$this->command = $this->getApplication()
//			->find($input->getArgument('command_name'));
//		}
//		$helper = new DescriptorHelper();
//		$helper->describe(
//			$output,
//			$this->command,
//			[
//				'format'   => $input->getOption('format'),
//			]
//		);
//		$this->command = null;
	}



}
*/
