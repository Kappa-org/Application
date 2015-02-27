<?php
/**
 * This file is part of the Kappa\Application package.
 *
 * (c) Ondřej Záruba <zarubaondra@gmail.com>
 *
 * For the full copyright and license information, please view the license.md
 * file that was distributed with this source code.
 *
 * @testCase
 */

namespace Kappa\Application\Tests;

use Kappa\Application\Routes\RouteFactory;
use KappaTests\Application\Tests\ContainerTestCase;
use Tester\Assert;

require_once __DIR__ . '/../../bootstrap.php';

/**
 * Class RouteFactoryTest
 * @package Kappa\Application\Tests
 */
class RouteFactoryTest extends ContainerTestCase
{
	/** @var \Kappa\Application\Routes\RouteFactory */
	private $routeFactory;

	protected function setUp()
	{
		$this->routeFactory = new RouteFactory($this->getContainer());
	}

	public function testCreateRoute()
	{
		Assert::count(1, $this->routeFactory->createRoute());
	}
}

\run(new RouteFactoryTest());
