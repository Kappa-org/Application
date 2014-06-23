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

/**
 * Class ApplicationExtension
 * @package Kappa\Application\DI
 */
class ApplicationExtension extends CompilerExtension
{
	const TAG_ROUTE_FACTORY = 'kappa.routeFactory';

	/** @var array */
	private $defaultConfig = array(
		'mapping' => true
	);

	public function loadConfiguration()
	{
		$builder = $this->getContainerBuilder();
		$config = $this->getConfig($this->defaultConfig);

		$builder->addDefinition($this->prefix('urlMatcher'))
			->setClass('Kappa\Application\Helpers\UrlMatcher');

		$builder->addDefinition($this->prefix('routeFactory'))
			->setClass('Kappa\Application\Routes\RouteFactory');

		if ($config['mapping']) {
			$appDir = isset($builder->parameters['appDir']) ? $builder->parameters['appDir'] : null;
			$builder->getDefinition('nette.presenterFactory')
				->setFactory('Kappa\Application\PresenterFactory', array($appDir));
		}
	}

	public function beforeCompile()
	{
		$builder = $this->getContainerBuilder();
		$routeFactory = $builder->getDefinition($this->prefix('routeFactory'));

		foreach ($builder->findByTag(self::TAG_ROUTE_FACTORY) as $name => $_) {
			$routeFactory->addSetup('addRoute', array('@'. $name));
		}

		$builder->getDefinition('router')
			->setFactory($this->prefix('@routeFactory') . '::createRoute');
	}
}