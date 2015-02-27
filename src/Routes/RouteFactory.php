<?php
/**
 * This file is part of the Kappa\Application package.
 *
 * (c) Ondřej Záruba <zarubaondra@gmail.com>
 *
 * For the full copyright and license information, please view the license.md
 * file that was distributed with this source code.
 */

namespace Kappa\Application\Routes;

use Nette\Application\Routers\RouteList;
use Nette\DI\Container;
use Nette\Object;

/**
 * Class RouterFactory
 * @package Kappa\Application\Routes
 */
class RouteFactory extends Object
{
	/** @var \Nette\DI\Container */
	private $container;

	/**
	 * @param Container $container
	 */
	public function __construct(Container $container)
	{
		$this->container = $container;
	}

	/**
	 * @return \Nette\Application\IRouter
	 */
	public function createRoute()
	{
		$routeFactories = $this->container->findByType('Kappa\Application\Routes\IRouteFactory');
		$route = new RouteList();
		/** @var \Kappa\Application\Routes\IRouteFactory $factory */
		foreach ($routeFactories as $factory) {
			$route[] = $this->container->getService($factory)->createRouter();
		}

		return $route;
	}
} 