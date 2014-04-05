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

use Kappa\Forms\Controls\AntiSpam;
use Kappa\Forms\Controls\DatePicker;
use Nette\Application\UI\Form as BaseForm;


/**
 * Class Form
 * @package Kappa\Application\UI
 */
class Form extends BaseForm
{
	/**
	 * @param string $name
	 * @param string|null $label
	 * @return AntiSpam
	 */
	public function addAntiSpam($name, $label = null)
	{
		$control = new AntiSpam($label);

		return $this[$name] = $control;
	}

	/**
	 * @param string $name
	 * @param string|null $label
	 * @return DatePicker
	 */
	public function addDatePicker($name, $label = null)
	{
		$control = new DatePicker($label);

		return $this[$name] = $control;
	}
}
