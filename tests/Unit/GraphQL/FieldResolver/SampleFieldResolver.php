<?php

declare(strict_types=1);

namespace DmitryRechkin\Tests\Unit\GraphQL\FieldResolver;

use DmitryRechkin\GraphQL\FieldResolver\FieldResolverInterface;
use GraphQL\Deferred;
use GraphQL\Type\Definition\ResolveInfo;

class SampleFieldResolver implements FieldResolverInterface
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
	public function __invoke($objectValue, array $args, $contextValue, ResolveInfo $info): Deferred
	{
		$result = ['id' => 1, 'message' => $args['message']['message'] ?? 'TITLE#1'];

		return new Deferred(function () use ($result) {
			return $result;
		});
	}
}
