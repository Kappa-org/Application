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

use Kappa\Application\Helpers\LinkGenerator;
use Kappa\Tester\MockTestCase;
use Tester\Assert;

require_once __DIR__ . '/../../bootstrap.php';

/**
 * Class LinkGeneratorTest
 * @package Kappa\Application\Tests
 */
class LinkGeneratorTest extends MockTestCase
{
	/** @var \Nette\Application\IPresenter */
	private $presenterMock;

	/** @var \Nette\Application\Application */
	private $applicationMock;

	/** @var \Kappa\Application\Helpers\LinkGenerator */
	private $linkGenerator;

	protected function setUp()
	{
		parent::setUp();
		$this->presenterMock = $this->mockista->create('Nette\Application\IPresenter');
		$this->presenterMock->expects('link')->andReturn('/url');
		$this->applicationMock = $this->mockista->create('Nette\Application\Application');
		$this->applicationMock->expects('getPresenter')->andReturn($this->presenterMock);
		$this->linkGenerator = new LinkGenerator($this->applicationMock);
	}

	public function testGetLink()
	{
		Assert::same('/url', $this->linkGenerator->getLink('Homepage:default'));
	}

	public function testCustomPresenter()
	{
		$linkGenerator = new LinkGenerator($this->applicationMock);
		$presenter = $this->mockista->create('Nette\Application\IPresenter');
		$presenter->expects('link')->andReturn('/url2');
		Assert::type(get_class($this->linkGenerator), $linkGenerator->setPresenter($presenter));
		Assert::same('/url2', $linkGenerator->getLink('Test:test'));
	}
}

\run(new LinkGeneratorTest());