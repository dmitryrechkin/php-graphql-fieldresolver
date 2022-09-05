<?php

declare(strict_types=1);

namespace DmitryRechkin\GraphQL\FieldResolver;

use GraphQL\Type\Definition\ResolveInfo;

interface ClassNameBuilderInterface
{
	/**
	 * builds and returns class name for a given resolve info
	 *
	 * @param ResolveInfo $info
	 * @return string
	 */
	public function build(ResolveInfo $info): ?string;
}
