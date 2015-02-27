<?php
/**
 * This file is part of the Kappa\Application package.
 *
 * (c) Ondřej Záruba <zarubaondra@gmail.com>
 *
 * For the full copyright and license information, please view the license.md
 * file that was distributed with this source code.
 */

namespace Kappa\Application\UI;

use Kappa\Application\InvalidArgumentException;
use Nette\Application\UI\Presenter;

/**
 * Class SecuredPresenter
 * @package Kappa\Application\UI
 */
abstract class SecuredPresenter extends Presenter
{
	/** @var string */
	protected $authLink = ':Admin:Auth:login';

	/** @var string */
	protected $logoutFlashMessage = array(
		'type' => 'success',
		'message' => 'Account has been logged out'
	);

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
		if (!isset($this->logoutFlashMessage['message']) || !isset($this->logoutFlashMessage['type'])) {
			throw new InvalidArgumentException("Missing 'message' or 'type' index in logoutFLashMessage property");
		}
		$this->flashMessage($this->logoutFlashMessage['message'], $this->logoutFlashMessage['type']);
		$this->redirect($this->authLink);
	}

	protected function beforeRender()
	{
		parent::beforeRender();
		$this->template->robots = "noindex, nofollow";
	}
}