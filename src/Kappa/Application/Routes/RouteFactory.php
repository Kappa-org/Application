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

use Kappa\Application\InvalidArgumentException;
use Nette\Application\IRouter;
use Nette\Application\Routers\RouteList;
use Nette\Object;

/**
 * Class RouterFactory
 * @package Kappa\Application\Routes
 */
class RouteFactory extends Object
{
	/** @var array */
	private $lists = array();

	public function __construct()
	{
		$this->lists = new RouteList();
	}

	/**
	 * @param IRouteFactory|\Nette\Application\IRouter $rout
	 * @return $this
	 * @throws \Kappa\Application\InvalidArgumentException
	 */
	public function addRoute($rout)
	{
		if ($rout instanceof IRouter) {
			$this->lists[] = $rout;
		} elseif ($rout instanceof IRouteFactory) {
			$this->lists[] = $rout->createRouter();
		} else {
			throw new InvalidArgumentException('Route must be instance of Nette\Application\IRouter or Kappa\Application\Routes\IRouteFactory');
		}

		return $this;
	}

	/**
	 * @return \Nette\Application\IRouter
	 */
	public function createRoute()
	{
		return $this->lists;
	}
} 