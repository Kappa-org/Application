<?php
/**
 * This file is part of the Kappa package.
 *
 * (c) Ondřej Záruba <zarubaondra@gmail.com>
 *
 * For the full copyright and license information, please view the license.md
 * file that was distributed with this source code.
 */

namespace Kappa\Application\UI;

use Nette\Application\UI\Presenter;

/**
 * Class SecuredPresenter
 * @package Kappa\Application\UI
 */
abstract class SecuredPresenter extends Presenter
{
	/** @var string */
	protected $authLink = ':Admin:Auth:login';

	/**
	 * @param $element
	 */
	public function checkRequirements($element)
	{
		parent::checkRequirements($element);
		if (!$this->getUser()->isLoggedIn()) {
			$this->redirect($this->authLink, array('backlink' => $this->storeRequest()));
		}
	}

	public function handleLogout()
	{
		$this->getUser()->logout(true);
		$this->flashMessage('Account has been logged out', 'success');
		$this->redirect($this->authLink);
	}

	protected function beforeRender()
	{
		parent::beforeRender();
		$this->template->robots = "noindex, nofollow";
	}
}