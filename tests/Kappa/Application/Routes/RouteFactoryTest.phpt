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
use Kappa\Tester\TestCase;
use Nette\Application\Routers\RouteList;
use Nette\DI\Container;
use Tester\Assert;

require_once __DIR__ . '/../../bootstrap.php';
require_once __DIR__ . '/../../../data/Router.php';

/**
 * Class RouteFactoryTest
 * @package Kappa\Application\Tests
 */
class RouteFactoryTest extends TestCase
{
	/** @var \Kappa\Application\Routes\RouteFactory */
	private $routeFactory;

	/** @var \Nette\DI\Container */
	private $container;

	/**
	 * @param Container $container
	 */
	public function __construct(Container $container)
	{
		$this->container = $container;
	}

	protected function setUp()
	{
		$this->routeFactory = new RouteFactory($this->container);
	}

	public function testCreateRoute()
	{
		Assert::count(1, $this->routeFactory->createRoute());
	}
}

\run(new RouteFactoryTest(getContainer()));