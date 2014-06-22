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
			'\<module>App'
		);
		Assert::type(get_class($this->presenterFactory), $this->presenterFactory->setMapping($mapping));
		Assert::throws(function () {
			$this->presenterFactory->setMapping(array('App\*Module\Presenters\*Presenter'));
		}, 'Kappa\Application\InvalidStateException');
	}

	public function testFormatPresenterClass()
	{
		Assert::type(get_class($this->presenterFactory), $this->presenterFactory->setMapping(array('*' => 'App\<module>Module\Presenters\<module>\<presenter>Presenter')));
		$expected = 'App\TestModule\Presenters\Sub\TestPresenter';
		$presenter = 'Test:Sub:Test';
		Assert::same($expected, $this->presenterFactory->formatPresenterClass($presenter));
	}

	public function testUnformatPresenterClass()
	{
		Assert::type(get_class($this->presenterFactory), $this->presenterFactory->setMapping(array('*' => 'App\<module>Module\Presenters\<module>\<presenter>Presenter')));
		$expected = 'Test:Sub:Test';
		$class = 'App\TestModule\Presenters\Sub\TestPresenter';
		Assert::same($expected, $this->presenterFactory->unformatPresenterClass($class));
	}
}

\run(new PresenterFactoryTest());