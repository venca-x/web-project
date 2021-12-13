<?php

declare(strict_types=1);

namespace App\Tests;

use Tester;
use Tester\Assert;

require __DIR__ . '/../vendor/autoload.php';


class ExampleTest extends Tester\TestCase
{
	public function setUp()
	{
	}


	public function testSomething(): void
	{
		Assert::true(true);
	}
}

$test = new ExampleTest;
$test->run();
