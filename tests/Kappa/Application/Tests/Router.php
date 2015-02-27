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

use Kappa\Application\Routes\IRouteFactory;
use Nette\Application\Routers\RouteList;

/**
 * Class Router
 *
 * @package KappaTests\Application\Tests
 * @author Ondřej Záruba <http://zaruba-ondrej.cz>
 */
class Router implements IRouteFactory
{
	/**
	 * @return \Nette\Application\IRouter
	 */
	public function createRouter()
	{
		$router = new RouteList();

		return $router;
	}
}
