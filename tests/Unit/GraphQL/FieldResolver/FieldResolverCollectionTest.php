<?php

declare(strict_types=1);

namespace DmitryRechkin\Tests\Unit\GraphQL\FieldResolver;

use DmitryRechkin\GraphQL\FieldResolver\FieldResolverContainer;
use DmitryRechkin\GraphQL\FieldResolver\FieldResolverInterface;
use PHPUnit\Framework\TestCase;
use stdClass;

class FieldResolverContainerTest extends TestCase
{
	/**
	 * @var FieldResolverContainer
	 */
	private $fieldResolvers;

	/**
	 * @return void
	 */
	public function setUp(): void
	{
		$this->fieldResolvers = new FieldResolverContainer();
		$this->fieldResolvers->add('query', 'sample', SampleFieldResolver::class);
		$this->fieldResolvers->add('query', 'test', stdClass::class);
	}

	/**
	 * @return void
	 */
	public function testHasReturnsTrueForExistingFieldName(): void
	{
		$this->assertTrue($this->fieldResolvers->has('query', 'sample'));
		$this->assertTrue($this->fieldResolvers->has('query', 'test'));
	}

	/**
	 * @return void
	 */
	public function testHasReturnsFalseForNonExistingFieldName(): void
	{
		$this->assertFalse($this->fieldResolvers->has('query', 'non-existing'));
	}

	/**
	 * @return void
	 */
	public function testMakeReturnsInstanceOfFieldResolverInterfaceForExistingFieldName(): void
	{
		$this->assertTrue($this->fieldResolvers->make('query', 'sample') instanceof FieldResolverInterface);
	}

	/**
	 * @return void
	 */
	public function testMakeReturnsNullForObjectWhichIsNotAnInstanceOfFieldResolverInterface(): void
	{
		$this->assertTrue(null === $this->fieldResolvers->make('query', 'test'));
	}

	/**
	 * @return void
	 */
	public function testMakeReturnsNullForNonExistingField(): void
	{
		$this->assertTrue(null === $this->fieldResolvers->make('query', 'non-existing'));
	}
}
