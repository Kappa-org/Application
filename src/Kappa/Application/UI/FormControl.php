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

use Nette\Application\UI\Control;
use Nette\Forms\Form;

/**
 * Class FormControl
 * @package Kappa\Application\UI
 */
class FormControl extends Control
{
	/** @var \Nette\Application\UI\Form */
	private $form;

	/**
	 * @param Form $form
	 */
	public function __construct(Form $form)
	{
		$this->form = $form;
	}

	/**
	 * @return Form
	 */
	protected function createComponentForm()
	{
		return $this->form;
	}

	/**
	 * @param string|null $file
	 */
	public function render($file = null)
	{
		$this->template->setFile(($file) ? : __DIR__ . '/templates/defaultForm.latte');
		$this->template->render();
	}
} 