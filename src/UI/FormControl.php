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
	
	/** @var null|string */
	private $defaultFile;

	/**
	 * FormControl constructor.
	 * @param Form $form
	 * @param string|null $defaultFile
	 */
	public function __construct(Form $form, $defaultFile = null
	)
	{
		$this->form = $form;
		$this->defaultFile = $defaultFile;
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
		if ($file === null) {
			if ($this->defaultFile !== null) {
				$file = $this->defaultFile;
			} else {
				$file = __DIR__ . '/templates/defaultForm.latte';
			}
		}
		$this->template->setFile($file);
		$this->template->render();
	}
} 