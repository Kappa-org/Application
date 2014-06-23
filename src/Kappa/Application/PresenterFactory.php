<?php
/**
 * This file is part of the Kappa\Application package.
 *
 * (c) Ondřej Záruba <zarubaondra@gmail.com>
 *
 * For the full copyright and license information, please view the license.md
 * file that was distributed with this source code.
 */

namespace Kappa\Application;

use Nette\Application\InvalidPresenterException;
use Nette\Reflection\ClassType;
use Nette\Utils\Strings;

/**
 * Class PresenterFactory
 * @package Kappa\Application
 */
class PresenterFactory extends \Nette\Application\PresenterFactory
{
	/** @var array */
	private $cache = array();

	/**
	 * @param array $mapping
	 * @return $this|\Nette\Application\PresenterFactory
	 * @throws InvalidStateException
	 */
	public function setMapping(array $mapping)
	{
		foreach ($mapping as $module => $mask) {
			if (!preg_match('~^\\\\?((\w+)?(<presenter>|<module([1-9]{1}[0-9]*)?>)?(\w+)?\\\\?)+((\w+)?(<presenter>|<module([1-9]{1}[0-9]*)?>)?(\w+)?)$~', $mask, $m)
				|| (strpos($mask, '<module>') !== false && preg_match('~<module[1-9]{1}[0-9]*>~', $mask))
			) {
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
		$mask = isset($parts[1], $this->mapping[$parts[0]])
			? $this->mapping[array_shift($parts)]
			: $this->mapping['*'];
		$mapping = str_replace('<presenter>', $presenterName, $mask);
		if (strpos($mapping, '<module>') !== false) {
			$count = substr_count($mapping, '<module>');
			if ($count != count($parts)) {
				throw new InvalidStateException("No mapping mask for '$presenter'");
			}
			foreach ($parts as $module) {
				$mapping = preg_replace('~<module>~', $module, $mapping, 1);
			}
		} else {
			if (preg_match_all('~<module([1-9]{1}[0-9]*)>~i', $mapping, $matches)) {
				foreach ($matches[0] as $key => $pattern) {
					$mapping = str_replace($pattern, $parts[$matches[1][$key] - 1], $mapping);
				}
				if (preg_match('~<module[1-9]{1}[0-9]*>~i', $mapping)) {
					throw new InvalidStateException("Mask '{$mask}' is not compatible with '{$presenter}' name");
				}
			}
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
		$presenters = array();
		foreach ($this->mapping as $module => $mask) {
			$replace = array(
				'~\\\\~' => '\\\\\\\\',
				'~<presenter>~' => '(\w+)',
				'~<module([1-9]{1}[0-9]*)?>~' => '(\w+)'
			);
			$mapping = preg_replace(array_keys($replace), array_values($replace), $mask);
			if (preg_match("~$mapping~i", $class, $matches)) {
				unset($matches[0]);
				if (preg_match_all('~<module([1-9]{1}[0-9]*)>~i', $mask, $order)) {
					asort($order[1]);
					$sortedMatches = array();
					foreach ($order[1] as $index => $position) {
						$sortedMatches[] = $matches[$index + 1];
						unset($matches[$index + 1]);
					}

					$matches = array_merge($sortedMatches, $matches);
				}
				$presenters[] = ($module === '*' ? '' : $module . ':') . implode(':', $matches);
			}
		}

		return $presenters;
	}

	/**
	 * Generates and checks presenter class name.
	 * @param  string  presenter name
	 * @return string  class name
	 * @throws InvalidPresenterException
	 */
	public function getPresenterClass(& $name)
	{
		if (isset($this->cache[$name])) {
			list($class, $name) = $this->cache[$name];
			return $class;
		}

		if (!is_string($name) || !Strings::match($name, '#^[a-zA-Z\x7f-\xff][a-zA-Z0-9\x7f-\xff:]*\z#')) {
			throw new InvalidPresenterException("Presenter name must be alphanumeric string, '$name' is invalid.");
		}

		$class = $this->formatPresenterClass($name);

		if (!class_exists($class)) {
			// internal autoloading
			$file = $this->formatPresenterFile($name);
			if (is_file($file) && is_readable($file)) {
				call_user_func(function() use ($file) { require $file; });
			}

			if (!class_exists($class)) {
				throw new InvalidPresenterException("Cannot load presenter '$name', class '$class' was not found in '$file'.");
			}
		}

		$reflection = new ClassType($class);
		$class = $reflection->getName();

		if (!$reflection->implementsInterface('Nette\Application\IPresenter')) {
			throw new InvalidPresenterException("Cannot load presenter '$name', class '$class' is not Nette\\Application\\IPresenter implementor.");
		}

		if ($reflection->isAbstract()) {
			throw new InvalidPresenterException("Cannot load presenter '$name', class '$class' is abstract.");
		}

		// canonicalize presenter name
		$realNames = $this->unformatPresenterClass($class);
		if ($this->caseSensitive) {
			$index = array_search(strtolower($name), array_map('strtolower', $realNames));
		} else {
			$index = array_search($name, $realNames);
		}
		if ($index !== false) {
			$realName = $realNames[$index];
			$this->cache[$name] = array($class, $realName);
			$name = $realName;
		}

		return $class;
	}
} 