<?php

declare(strict_types=1);

namespace DmitryRechkin\Tests\Unit\GraphQL\FieldResolver;

use DmitryRechkin\GraphQL\FieldResolver\ClassNameBuilder;
use GraphQL\Type\Definition\ResolveInfo;
use PHPUnit\Framework\TestCase;
use stdClass;

class ClassNameBuilderTest extends TestCase
{
	/**
	 * @return void
	 */
	public function testBuildMethodReturnsClassNameWithFieldResolverSuffix(): void
	{
		$resolveInfoMock = $this->getMockBuilder(ResolveInfo::class)
			->disableOriginalConstructor()
			->getMock();
		$resolveInfoMock->operation = new stdClass();
		$resolveInfoMock->operation->operation = 'query';
		$resolveInfoMock->fieldName = 'classNameBuilderTest';

		$classNameBuilder = new ClassNameBuilder(__NAMESPACE__);
		$this->assertSame(ClassNameBuilderTestQueryFieldResolver::class, $classNameBuilder->build($resolveInfoMock));
	}

	/**
	 * @return void
	 */
	public function testBuildMethodReturnsClassNameWithoutFieldResolverSuffix(): void
	{
		$resolveInfoMock = $this->getMockBuilder(ResolveInfo::class)
			->disableOriginalConstructor()
			->getMock();
		$resolveInfoMock->operation = new stdClass();
		$resolveInfoMock->operation->operation = 'mutation';
		$resolveInfoMock->fieldName = 'classNameBuilderWithoutSuffixTest';

		$classNameBuilder = new ClassNameBuilder(__NAMESPACE__);
		$this->assertSame(ClassNameBuilderWithoutSuffixTestMutation::class, $classNameBuilder->build($resolveInfoMock));
	}
}
