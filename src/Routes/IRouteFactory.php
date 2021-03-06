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

/**
 * Interface IRouteFactory
 * @package Kappa\Application\Routes
 */
interface IRouteFactory
{
	/**
	 * @return \Nette\Application\IRouter
	 */
	public function createRouter();
} 