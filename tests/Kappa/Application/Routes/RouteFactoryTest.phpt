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

	protected function setUp()
	{
		$this->routeFactory = new RouteFactory();
	}

	public function testCreateRoute()
	{
		Assert::type(get_class($this->routeFactory), $this->routeFactory->addRoute(new \Router()));
		Assert::type(get_class($this->routeFactory), $this->routeFactory->addRoute(new RouteList()));
		Assert::count(2, $this->routeFactory->createRoute());
		Assert::throws(function () {
			$this->routeFactory->addRoute(new \stdClass());
		}, 'Kappa\Application\InvalidArgumentException');
	}
}

\run(new RouteFactoryTest(getContainer()));