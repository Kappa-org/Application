<?php
/**
 * This file is part of the application package.
 *
 * (c) Ondřej Záruba <zarubaondra@gmail.com>
 *
 * For the full copyright and license information, please view the license.md
 * file that was distributed with this source code.
 * 
 * @testCase
 */

namespace Kappa\Application\Tests;

use Kappa\Application\PresenterFactory;
use Kappa\Tester\MockTestCase;
use Tester\Assert;

require_once __DIR__ . '/../bootstrap.php';

/**
 * Class PresenterFactoryTest
 * @package application\Tests
 */
class PresenterFactoryTest extends MockTestCase
{
	/** @var \Kappa\Application\PresenterFactory */
	private $presenterFactory;

	protected function setUp()
	{
		parent::setUp();
		$containerMock = $this->mockista->create('Nette\DI\Container');
		$this->presenterFactory = new PresenterFactory(__DIR__, $containerMock);
	}

	public function testSetMapping()
	{
		$mapping = array(
			'App\<module>Module\Presenters\<presenter>Presenter',
			'App\<module>\Presenters\Presenter<presenter>',
			'App',
			'\<module>App'
		);
		Assert::type(get_class($this->presenterFactory), $this->presenterFactory->setMapping($mapping));
		Assert::throws(function () {
			$this->presenterFactory->setMapping(array('App\*Module\Presenters\*Presenter'));
		}, 'Kappa\Application\InvalidStateException');
		Assert::throws(function () {
			$this->presenterFactory->setMapping(array('<module>\<module1>'));
		}, 'Kappa\Application\InvalidStateException');
	}

	public function testFormatPresenterClass()
	{
		$mapping = array(
			'Module' => 'App\<module>Module\Presenters\<module>\<presenter>Presenter',
			'*' => 'App\<module>Module\<module>\Presenters\<module>\<presenter>Presenter',
		);
		Assert::type(get_class($this->presenterFactory), $this->presenterFactory->setMapping($mapping));
		Assert::same('App\TestModule\Presenters\Sub\TestPresenter', $this->presenterFactory->formatPresenterClass('Module:Test:Sub:Test'));
		Assert::same('App\SomeModule\Test\Presenters\Sub\TestPresenter', $this->presenterFactory->formatPresenterClass('Some:Test:Sub:Test'));
		Assert::throws(function () {
			$this->presenterFactory->formatPresenterClass('Too:long:Some:Test:Sub:Test');
		}, 'Kappa\Application\InvalidStateException');
	}

	public function testUnformatPresenterClass()
	{
		$mapping = array(
			'Module' => 'App\<module>Module\Presenters\<module>\<presenter>Presenter',
			'*' => 'App\<module>Module\<module>\Presenters\<module>\<presenter>Presenter',
		);
		Assert::type(get_class($this->presenterFactory), $this->presenterFactory->setMapping($mapping));
		Assert::same('Module:Test:Sub:Test', $this->presenterFactory->unformatPresenterClass('App\TestModule\Presenters\Sub\TestPresenter'));
		Assert::same('Some:Test:Sub:Test', $this->presenterFactory->unformatPresenterClass('App\SomeModule\Test\Presenters\Sub\TestPresenter'));
		Assert::null($this->presenterFactory->unformatPresenterClass('Some:Name'));
	}
}

\run(new PresenterFactoryTest());