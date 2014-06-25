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

use Kappa\Forms\IFormFactory;
use Nette\Application\UI\Control;

/**
 * Class FormControl
 * @package Kappa\Application\UI
 */
class FormControl extends Control
{
	/** @var \Kappa\Forms\IFormFactory */
	private $formFactory;

	/** @var mixed|null */
	private $args;

	/**
	 * @param IFormFactory $formFactory
	 * @param null|mixed $args
	 */
	public function __construct(IFormFactory $formFactory, $args = null)
	{
		$this->formFactory = $formFactory;
		$this->args = $args;
	}

	/**
	 * @return \Nette\Forms\Form
	 */
	protected function createComponentForm()
	{
		return $this->formFactory->create($this->args);
	}

	/**
	 * @param string $file
	 */
	public function render($file)
	{
		$this->template->setFile($file);
		$this->template->render();
	}
} 