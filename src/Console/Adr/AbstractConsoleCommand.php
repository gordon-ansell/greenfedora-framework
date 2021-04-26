<?php

/**
 * This file is part of the GordyAnsell GreenFedora PHP framework.
 *
 * (c) Gordon Ansell <contact@gordonansell.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);
namespace GreenFedora\Console\Adr;

use GreenFedora\Application\Adr\AbstractAction;
use GreenFedora\Console\ConsoleRequestInterface;
use GreenFedora\Console\ConsoleResponseInterface;
use GreenFedora\Console\Adr\ConsoleCommandInterface;
use GreenFedora\DI\ContainerInterface;
use GreenFedora\TextBuffer\TextBufferInterface;

/**
 * The base for console actions.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */

abstract class AbstractConsoleCommand extends AbstractAction implements ConsoleCommandInterface
{
	/**
	 * Input.
	 * @var ConsoleRequestInterface
	 */
	protected $request = null;

	/**
	 * Output.
	 * @var ConsoleResponseInterface
	 */
	protected $response = null;

	/**
	 * Help.
	 * @var array
	 */
	protected static $help = [
		'name' => '',
		'description' => '',
	];

	/**
	 * Constructor.
	 *
	 * @param 	ContainerInterface			$container	Dependency injection container.
	 * @param 	ConsoleRequestInterface		$request 	Input.
	 * @param 	ConsoleResponseInterface	$response 	Output.
	 * @param 	array						$params 	Parameters.
	 * @return	void
	 */
	public function __construct(ContainerInterface $container, ConsoleRequestInterface $request, 
		ConsoleResponseInterface $response, array $params = [])
	{
        parent::__construct($container, $request, $response, $params);
	}

	/**
	 * Get the help.
	 * 
	 * @return 	array
	 */
	public static function getHelp(): array
	{
		return static::$help;
	}

	/**
	 * Do the auto help.
	 * 
	 * @param 	array 	$classes 	Classes to process.
	 * @return 	array
	 */
	public function autoHelp(array $classes): array
	{
		$ret = [];
		foreach ($classes as $c) {
			$tmp = $c::getHelp();
			if (array_key_exists('detail', $tmp)) {
				$ret[$tmp['name']] = [$tmp['description'], $tmp['detail']];
			} else {
				$ret[$tmp['name']] = $tmp['description'];
			}
		}
		return $ret;
	}

	/**
	 * Print the auto help.
	 * 
	 * @param 	array 					$classes 	Classes to process.
	 * @param 	TextBufferInterface 	$tb			Buffer to write to.
	 * @param 	int 					$pad 		Padding between key and item.
	 * @return 	array
	 */
	public function autoHelpPrint(array $classes, TextBufferInterface &$tb, int $pad = 30): TextBufferInterface
	{
		foreach($this->autoHelp($classes) as $k => $v) {
			if (is_array($v)) {
				$tb->writeln(str_pad($k, $pad) . $v[0]);
				$tb->blank();
				foreach($v[1] as $detailk => $detailv) {
					$tb->writeln(str_pad(' ', 4)  . str_pad($detailk, $pad - 4) . $detailv);
				}
				$tb->blank();
			} else {
				$tb->writeln(str_pad($k, $pad) . $v);
			}
		}
		return $tb;
	}
}