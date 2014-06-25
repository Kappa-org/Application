<?php
/**
 * This file is part of the application package.
 *
 * (c) Ondřej Záruba <zarubaondra@gmail.com>
 *
 * For the full copyright and license information, please view the license.md
 * file that was distributed with this source code.
 */

namespace Kappa\Application;

/**
 * Class PresenterFactory
 * @package Kappa\Application
 */
class PresenterFactory extends \Nette\Application\PresenterFactory
{
	/** @var array */
	private $mapping = array(
		'*' => '<module>Module\<presenter>Presenter'
	);

	/**
	 * @param array $mapping
	 * @return $this|\Nette\Application\PresenterFactory
	 * @throws InvalidStateException
	 */
	public function setMapping(array $mapping)
	{
		foreach ($mapping as $module => $mask) {
			if (!preg_match('~^\\\\?((\w+)?(<presenter>|<module>)?(\w+)?\\\\?)+((\w+)?(<presenter>|<module>)?(\w+)?)$~', $mask, $m)) {
				throw new InvalidStateException("Invalid mapping mask '$mask'.");
			}
			$this->mapping[$module] = $m[0];
		}

		return $this;
	}

	/**
	 * @param string $presenter
	 * @return string
	 * @throws InvalidStateException
	 */
	public function formatPresenterClass($presenter)
	{
		$parts = explode(':', $presenter);
		$presenterName = end($parts);
		unset($parts[count($parts) - 1]);
		$mask = isset($parts[0], $this->mapping[$parts[0]])
			? $this->mapping[array_shift($parts)]
			: $this->mapping['*'];
		$mapping = str_replace('<presenter>', $presenterName, $mask);
		$count = substr_count($mapping, '<module>');
		if ($count != count($parts)) {
			throw new InvalidStateException("No mapping mask for '$presenter'");
		}
		foreach ($parts as $module) {
			$mapping = preg_replace('~<module>~', $module, $mapping, 1);
		}

		return $mapping;
	}

	/**
	 * Formats presenter name from class name.
	 * @param  string
	 * @return string
	 */
	public function unformatPresenterClass($class)
	{
		foreach ($this->mapping as $module => $mapping) {
			$mapping = str_replace(array('\\', '<presenter>', '<module>'), array('\\\\', '(\w+)', '(\w+)'), $mapping);
			if (preg_match("~$mapping~", $class, $matches)) {
				unset($matches[0]);
 				return ($module === '*' ? '' : $module . ':') . implode(':', $matches);
 			}
 		}
	}
} 