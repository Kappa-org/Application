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

use KappaTests\Application\Tests\ContainerTestCase;
use Tester\Assert;

require_once __DIR__ . '/../../bootstrap.php';

/**
 * Class ApplicationExtensionTest
 *
 * @package Kappa\Application\Tests
 * @author Ondřej Záruba <http://zaruba-ondrej.cz>
 */
class ApplicationExtensionTest extends ContainerTestCase
{
	public function testUrlMatcher()
	{
		$service = $this->getContainer()->getByType('Kappa\Application\Helpers\UrlMatcher');
		Assert::type('Kappa\Application\Helpers\UrlMatcher', $service);
	}

	public function testRouterFactory()
	{
		$service = $this->getContainer()->getByType('Kappa\Application\Routes\RouteFactory');
		Assert::type('Kappa\Application\Routes\RouteFactory', $service);
		Assert::count(1, $service->createRoute());
	}
}

\run(new ApplicationExtensionTest());
