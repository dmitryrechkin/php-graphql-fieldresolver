<?php

declare(strict_types=1);

namespace DmitryRechkin\GraphQL\FieldResolver;

use DI\Container;

class FieldResolverContainer
{
	/**
	 * @var array
	 */
	private $resolvers;

	/**
	 * @var Container
	 */
	private $container;

	/**
	 * constructor
	 *
	 * @param Container $container
	 */
	public function __construct(Container $container = null)
	{
		$this->resolvers = [];
		$this->container = $container ?? new Container();
	}

	/**
	 * adds resolver class name
	 *
	 * @param string $operation
	 * @param string $fieldName
	 * @param string $resolverClassName
	 * @return void
	 */
	public function add(string $operation, string $fieldName, string $resolverClassName): void
	{
		if (class_exists($resolverClassName)) {
			$key = $this->getKey($operation, $fieldName);
			$this->resolvers[$key] = $resolverClassName;
		}
	}

	/**
	 * returns true when field's resolver is present
	 *
	 * @param string $operation
	 * @param string $fieldName
	 * @return boolean
	 */
	public function has(string $operation, string $fieldName): bool
	{
		return null !== $this->getClassNameFor($operation, $fieldName);
	}

	/**
	 * returns an instance of a requested field's resolver when it is present
	 *
	 * @param string $operation
	 * @param string $fieldName
	 * @return FieldResolverInterface
	 */
	public function make(string $operation, string $fieldName): ?FieldResolverInterface
	{
		$resolverClassName = $this->getClassNameFor($operation, $fieldName);
		if ($resolverClassName === null) {
			return null;
		}

		$resolver = $this->container->make($resolverClassName);
		if ($resolver instanceof FieldResolverInterface) {
			return $resolver;
		}

		return null;
	}

	/**
	 * returns resolver class name
	 *
	 * @param string $operation
	 * @param string $fieldName
	 * @return string
	 */
	private function getClassNameFor(string $operation, string $fieldName): ?string
	{
		$key = $this->getKey($operation, $fieldName);

		return $this->resolvers[$key] ?? null;
	}

	/**
	 * returns key from a given operation and field name
	 *
	 * @param string $operation
	 * @param string $fieldName
	 * @return string
	 */
	private function getKey(string $operation, string $fieldName): string
	{
		return $operation . '::' . $fieldName;
	}
}
