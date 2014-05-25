<?php
/**
 * This file is part of the application package.
 *
 * (c) Ondřej Záruba <zarubaondra@gmail.com>
 *
 * For the full copyright and license information, please view the license.md
 * file that was distributed with this source code.
 */

namespace Kappa\Application\Helpers;

use Nette\Application\Application;
use Nette\Application\IPresenter;
use Nette\Object;

/**
 * Class LinkGenerator
 * @package Kappa\Application\Helpers
 */
class LinkGenerator extends Object
{
	/** @var \Nette\Application\IPresenter */
	private $presenter;

	/**
	 * @param Application $application
	 */
	public function __construct(Application $application)
	{
		$this->presenter= $application->getPresenter();
	}

	/**
	 * @param IPresenter $presenter
	 * @return $this
	 */
	public function setPresenter(IPresenter $presenter)
	{
		$this->presenter = $presenter;
		
		return $this;
	}

	/**
	 * @param string $destination
	 * @param array $args
	 * @return string
	 */
	public function getLink($destination, array $args = array())
	{
		return $this->presenter->link($destination, $args);
	}
} 