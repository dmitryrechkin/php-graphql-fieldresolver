<?php

declare(strict_types=1);

namespace DmitryRechkin\Tests\Unit\GraphQL\FieldResolver;

use DI\Container;
use DmitryRechkin\GraphQL\FieldResolver\ClassNameBuilderInterface;
use DmitryRechkin\GraphQL\FieldResolver\FieldResolverInterface;
use DmitryRechkin\GraphQL\FieldResolver\LazyFieldResolver;
use GraphQL\Deferred;
use GraphQL\Type\Definition\ResolveInfo;
use PHPUnit\Framework\TestCase;

class LazyFieldResolverTest extends TestCase
{
	/**
	 * @return void
	 */
	public function testLazyFieldResolverReturnsExpectedResult(): void
	{
		$expectedResponse = new Deferred(function () {
			return 'OK';
		});
		$fieldResolverClassName = 'TestFieldResolver';

		$classNameBuilderMock = $this->createMock(ClassNameBuilderInterface::class);
		$classNameBuilderMock
			->expects($this->once())
			->method('build')
			->willReturn($fieldResolverClassName);

		$fieldResolverMock = $this->createMock(FieldResolverInterface::class);
		$fieldResolverMock
			->expects($this->once())
			->method('__invoke')
			->willReturn($expectedResponse);

		$containerMock = $this->createMock(Container::class);
		$containerMock
			->expects($this->once())
			->method('make')
			->with($fieldResolverClassName)
			->willReturn($fieldResolverMock);

		$lazyFieldResolver = new LazyFieldResolver($classNameBuilderMock, $containerMock);

		$this->assertSame($expectedResponse, $lazyFieldResolver(null, [], null, $this->createMock(ResolveInfo::class)));
	}
}
