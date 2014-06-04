<?php
/**
 * This file is part of the Kappa\Application package.
 *
 * (c) Ondřej Záruba <zarubaondra@gmail.com>
 *
 * For the full copyright and license information, please view the license.md
 * file that was distributed with this source code.
 */

namespace Kappa\Application\Helpers;

use Kappa\Application\InvalidArgumentException;
use Kappa\Application\InvalidStateException;
use Nette\Application\Application;
use Nette\Application\Request as AppRequest;
use Nette\Http\Request as HttpRequest;
use Nette\Http\Url;
use Nette\Http\UrlScript;
use Nette\Object;

/**
 * Class UrlMatcher
 * @package Kappa\Application\Helpers
 */
class UrlMatcher extends Object
{
	/** @var \Nette\Application\Application */
	private $application;

	/**
	 * @param Application $application
	 */
	public function __construct(Application $application)
	{
		$this->application = $application;
	}

	/**
	 * @param string $url
	 * @return \Nette\Application\Request|NULL
	 * @throws \Kappa\Application\InvalidArgumentException
	 */
	public function urlToRequest($url)
	{
		if (!is_string($url)) {
			throw new InvalidArgumentException('Url must be string, ' . gettype($url) . ' given');
		}
		$router = $this->getRouter();
		$urlScript = new UrlScript($url);
		$httpRequest = new HttpRequest($urlScript);

		return $router->match($httpRequest);
	}

	/**
	 * @param AppRequest $appRequest
	 * @return string|null
	 */
	public function requestToUrl(AppRequest $appRequest)
	{
		$router = $this->getRouter();
		$url = $router->constructUrl($appRequest, new Url());
		if (preg_match('~^http[s]?://(.*)$~', $url, $output) && isset($output[1])) {
			return $output[1];
		} else {
			return null;
		}
	}

	/**
	 * @param string $destination
	 * @param array $args
	 * @return string|null
	 * @throws \Kappa\Application\InvalidArgumentException
	 */
	public function destinationToUrl($destination, array $args = array())
	{
		if (!is_string($destination)) {
			throw new InvalidArgumentException('Destination must be string, ' . gettype($destination) . ' given');
		}
		$end = strrchr($destination, ':');
		if ($end == ':') {
			$presenter = substr($destination, 0, '-1');
			$appRequest = new AppRequest($presenter, null, $args);
		} else {
			$args['action'] = $action = substr(strrchr($destination, ':'), 1);
			$presenter = substr($destination, 0, (strlen($action) + 1) * -1);
			$appRequest = new AppRequest($presenter, null, $args);
		}

		return $this->requestToUrl($appRequest);
	}

	/**
	 * @return \Nette\Application\IRouter
	 * @throws \Kappa\Application\InvalidStateException
	 */
	private function getRouter()
	{
		$router = $this->application->getRouter();
		if (!$router) {
			throw new InvalidStateException('Any router has not been found');
		}

		return $router;
	}
} 