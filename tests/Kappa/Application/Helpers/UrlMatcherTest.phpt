<?php
/**
 * This file is part of the Kappa\Application package.
 *
 * (c) Ondřej Záruba <zarubaondra@gmail.com>
 *
 * For the full copyright and license information, please view the license.md
 * file that was distributed with this source code.
 *
 * @testCase
 */

namespace Kappa\Application\Tests;

use Kappa\Application\Helpers\UrlMatcher;
use Nette\Application\Application;
use Nette\Application\Request;
use Nette\Application\Routers\Route;
use Nette\Application\Routers\RouteList;
use Tester\Assert;
use Tester\TestCase;

require_once __DIR__ . '/../../bootstrap.php';

/**
 * Class UrlMatcherTest
 * @package Kappa\Application\Tests
 */
class UrlMatcherTest extends TestCase
{
	/** @var \Kappa\Application\Helpers\UrlMatcher */
	private $urlMatcher;

	protected function setUp()
	{
		parent::setUp();
		$presenterFactoryMock = \Mockery::mock('Nette\Application\IPresenterFactory');
		$httpRequestMock = \Mockery::mock('Nette\Http\IRequest');
		$httpResponseMock = \Mockery::mock('Nette\Http\IResponse');
		$routeList = new RouteList();
		$routeList[] = new Route('/some/app/url/<id>', 'Module:Presenter:action');
		$application = new Application($presenterFactoryMock, $routeList, $httpRequestMock, $httpResponseMock);
		$this->urlMatcher = new UrlMatcher($application);
	}

	public function testUrlToRequest()
	{
		$request = $this->urlMatcher->urlToRequest('/some/app/url/5');
		Assert::same('Module:Presenter', $request->getPresenterName());
		Assert::equal(array('action' => 'action', 'id' => '5'), $request->getParameters());
		$self = $this;
		Assert::throws(function () use ($self) {
			$self->urlMatcher->urlToRequest(array('xxx'));
		}, 'Kappa\Application\InvalidArgumentException');
	}

	public function testRequestToUrl()
	{
		$appRequest = new Request('Module:Presenter', null, array('action' => 'action', 'id' => 10));
		$appRequest_wrong = new Request('No', null, array());
		Assert::same('/some/app/url/10', $this->urlMatcher->requestToUrl($appRequest));
		Assert::null($this->urlMatcher->requestToUrl($appRequest_wrong));
	}

	public function testDestinationToUrl()
	{
		$url = $this->urlMatcher->destinationToUrl('Module:Presenter:action', array('id' => 7));
		Assert::same('/some/app/url/7', $url);
		Assert::null($this->urlMatcher->destinationToUrl('No'));
		$self = $this;
		Assert::throws(function () use ($self) {
			$self->urlMatcher->destinationToUrl(array('xxxx'));
		}, 'Kappa\Application\InvalidArgumentException');
	}
}

\run(new UrlMatcherTest());
