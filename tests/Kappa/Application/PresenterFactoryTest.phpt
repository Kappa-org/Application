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

use Kappa\Application\PresenterFactory;
use Kappa\Tester\MockTestCase;
use Tester\Assert;

require_once __DIR__ . '/../bootstrap.php';
require_once __DIR__ . '/../../data/TestPresenter2.php';
require_once __DIR__ . '/../../data/TestPresenter.php';

/**
 * Class PresenterFactoryTest
 * @package Kappa\Application\Tests
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
			'\<module>App',
			'<module1>\<module2>'
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
			'Type1' => 'App\<module>Module\Presenters\<module>\<presenter>Presenter',
			'Type2' => 'App\<module2>Module\Presenters\<module1>\<presenter>Presenter'
		);
		Assert::type(get_class($this->presenterFactory), $this->presenterFactory->setMapping($mapping));
		Assert::same('App\TestModule\Presenters\Sub\TestPresenter', $this->presenterFactory->formatPresenterClass('Type1:Test:Sub:Test'));
		Assert::same('App\SubModule\Presenters\Test\TestPresenter', $this->presenterFactory->formatPresenterClass('Type2:Test:Sub:Test'));
	}

	public function testUnformatPresenterClass()
	{
		$mapping = array(
			'Type1' => 'App\<module>Module\Presenters\<module>\<presenter>Presenter',
			'Type2' => 'App\<module2>Module\Presenters\<module1>\<presenter>Presenter'
		);
		Assert::type(get_class($this->presenterFactory), $this->presenterFactory->setMapping($mapping));
		Assert::true(in_array('Type1:Test:Sub:Test', $this->presenterFactory->unformatPresenterClass('App\TestModule\Presenters\Sub\TestPresenter')));
		Assert::true(in_array('Type2:Test:Sub:Test', $this->presenterFactory->unformatPresenterClass('App\SubModule\Presenters\Test\TestPresenter')));
	}

	public function testGetPresenterClass()
	{
		$mapping = array(
			'Type1' => 'App\<module>Module\Presenters\<module>\<presenter>Presenter',
			'Type2' => 'App\<module2>Module\Presenters\<module1>\<presenter>Presenter'
		);
		Assert::type(get_class($this->presenterFactory), $this->presenterFactory->setMapping($mapping));
		$name1 = 'Type1:Test:Sub:Test';
		$name2 = 'Type2:test:sub:test';
		Assert::same('App\TestModule\Presenters\Sub\TestPresenter', $this->presenterFactory->getPresenterClass($name1));
		Assert::same('App\SubModule\Presenters\Test\TestPresenter', $this->presenterFactory->getPresenterClass($name2));
	}
}

\run(new PresenterFactoryTest());