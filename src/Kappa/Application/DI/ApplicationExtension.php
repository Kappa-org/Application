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

		if ($config['mapping']) {
			$appDir = isset($builder->parameters['appDir']) ? $builder->parameters['appDir'] : null;
			$builder->getDefinition('nette.presenterFactory')
				->setFactory('Kappa\Application\PresenterFactory', array($appDir));
		}
	}
}