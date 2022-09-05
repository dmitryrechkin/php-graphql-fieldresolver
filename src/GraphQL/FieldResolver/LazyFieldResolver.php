<?php

declare(strict_types=1);

namespace DmitryRechkin\GraphQL\FieldResolver;

use DI\Container;
use GraphQL\Deferred;
use GraphQL\Executor\Executor;
use GraphQL\Type\Definition\ResolveInfo;

class LazyFieldResolver implements FieldResolverInterface
{
	/**
	 * @var ClassNameBuilderInterface
	 */
	private $classNameBuilder;

	/**
	 * @var Container
	 */
	private $container;

	/**
	 * constructor
	 *
	 * @param ClassNameBuilderInterface $classNameBuilder
	 * @param Container $container
	 */
	public function __construct(ClassNameBuilderInterface $classNameBuilder, Container $container = null)
	{
		$this->classNameBuilder = $classNameBuilder;
		$this->container = $container ?? new Container();
	}

	/**
	 * resolves a given field
	 *
	 * @param mixed $objectValue
	 * @param array $args
	 * @param mixed $contextValue
	 * @param ResolveInfo $info
	 * @return mixed
	 */
	public function __invoke($objectValue, array $args, $contextValue, ResolveInfo $info): Deferred
	{
		$className = $this->classNameBuilder->build($info);
		if (null !== $className) {
			$resolver = $this->container->make($className);
			if ($resolver instanceof FieldResolverInterface) {
				return $resolver($objectValue, $args, $contextValue, $info);
			}
		}

		$result = Executor::defaultFieldResolver($objectValue, $args, $contextValue, $info);

		if ($result instanceof Deferred) {
			return $result;
		}

		return new Deferred(function () use ($result) {
			return $result;
		});
	}
}
