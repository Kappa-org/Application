<?php
/**
 * This file is part of the Kappa\Application package.
 *
 * (c) Ondřej Záruba <zarubaondra@gmail.com>
 *
 * For the full copyright and license information, please view the license.md
 * file that was distributed with this source code.
 */

namespace KappaTests\Application\Tests;

use Nette\Configurator;
use Tester\TestCase;

/**
 * Class ContainerTestCase
 *
 * @package KappaTests\Application\Tests
 * @author Ondřej Záruba <http://zaruba-ondrej.cz>
 */
class ContainerTestCase extends TestCase
{
	/**
	 * @return \Nette\DI\Container
	 */
	protected function getContainer()
	{
		$configurator = new Configurator();
		$configurator->setTempDirectory(TEMP_DIR);
		$configurator->addConfig(__DIR__ . '/../../../data/config.neon');

		return $configurator->createContainer();
	}
}
