<?php

declare(strict_types=1);

namespace DmitryRechkin\GraphQL\FieldResolver;

use GraphQL\Deferred;
use GraphQL\Type\Definition\ResolveInfo;

interface FieldResolverInterface
{
	/**
	 * resolves a field
	 *
	 * @param mixed $objectValue
	 * @param array $args
	 * @param mixed $contextValue
	 * @param ResolveInfo $info
	 * @return Deferred
	 */
	public function __invoke($objectValue, array $args, $contextValue, ResolveInfo $info): Deferred;
}
