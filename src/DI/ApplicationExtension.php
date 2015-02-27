<?php
/**
 * This file is part of the Kappa\Application package.
 *
 * (c) Ondřej Záruba <zarubaondra@gmail.com>
 *
 * For the full copyright and license information, please view the license.md
 * file that was distributed with this source code.
 */

namespace Kappa\Application\DI;

use Nette\DI\CompilerExtension;
use Nette\PhpGenerator\ClassType;

/**
 * Class ApplicationExtension
 * @package Kappa\Application\DI
 */
class ApplicationExtension extends CompilerExtension
{
	public function loadConfiguration()
	{
		$builder = $this->getContainerBuilder();

		$builder->addDefinition($this->prefix('urlMatcher'))
			->setClass('Kappa\Application\Helpers\UrlMatcher');

		$builder->addDefinition($this->prefix('routeFactory'))
			->setClass('Kappa\Application\Routes\RouteFactory');

		if ($builder->hasDefinition('routing.router')) {
			$router = $builder->getDefinition('routing.router');
		} else {
			$router = $builder->getDefinition('router');
		}
		$router->setFactory($this->prefix('@routeFactory') . '::createRoute');
	}
}
