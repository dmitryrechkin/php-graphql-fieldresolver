<?php

declare(strict_types=1);

namespace DmitryRechkin\GraphQL\FieldResolver;

use GraphQL\Deferred;
use GraphQL\Executor\Executor;
use GraphQL\Type\Definition\ResolveInfo;

class FieldResolversManager implements FieldResolverInterface
{
	/**
	 * @var FieldResolverContainer
	 */
	private $fieldResolvers;

	/**
	 * constructor
	 *
	 * @param FieldResolverContainer $FieldResolverContainer
	 */
	public function __construct(FieldResolverContainer $FieldResolverContainer = null)
	{
		$this->fieldResolvers = $FieldResolverContainer ?? new FieldResolverContainer();
	}

	/**
	 * returns field resolver container
	 *
	 * @return FieldResolverContainer
	 */
	public function getFieldResolvers(): FieldResolverContainer
	{
		return $this->fieldResolvers;
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
		if ($this->fieldResolvers->has($info->operation->operation, $info->fieldName)) {
			$resolver = $this->fieldResolvers->make($info->operation->operation, $info->fieldName);

			return $resolver($objectValue, $args, $contextValue, $info);
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
