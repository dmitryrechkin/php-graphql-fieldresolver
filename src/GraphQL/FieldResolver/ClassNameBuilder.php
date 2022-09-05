<?php

declare(strict_types=1);

namespace DmitryRechkin\GraphQL\FieldResolver;

use GraphQL\Type\Definition\ResolveInfo;

class ClassNameBuilder implements ClassNameBuilderInterface
{
	/**
	 * @var string
	 */
	private $namespace;

	/**
	 * constructor
	 *
	 * @param string $namespace
	 */
	public function __construct(string $namespace)
	{
		$this->namespace = rtrim($namespace, '\\') . '\\';
	}

	/**
	 * builds and returns class name for a given resolve info
	 *
	 * @param ResolveInfo $info
	 * @return string
	 */
	public function build(ResolveInfo $info): ?string
	{
		$className = $this->namespace;
		$className .= ucfirst($info->fieldName);
		$className .= ucfirst($info->operation->operation);

		if (class_exists($className)) {
			return $className;
		}

		$className .= 'FieldResolver';

		if (class_exists($className)) {
			return $className;
		}

		return null;
	}
}
