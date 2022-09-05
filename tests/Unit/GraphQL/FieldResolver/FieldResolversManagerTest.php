<?php

declare(strict_types=1);

namespace DmitryRechkin\Tests\Unit\GraphQL\FieldResolver;

use DmitryRechkin\GraphQL\FieldResolver\FieldResolversManager;
use GraphQL\Executor\Promise\Adapter\SyncPromiseAdapter;
use GraphQL\Type\Definition\ResolveInfo;
use PHPUnit\Framework\TestCase;
use stdClass;

class FieldResolversManagerTest extends TestCase
{
	/**
	 * @return void
	 */
	public function testFieldResolversManagerReturnsExpectedResult(): void
	{
		$expectedMessage = 'my test message';

		$infoMock = $this->createMock(ResolveInfo::class);
		$infoMock = $this->getMockBuilder(ResolveInfo::class)
			->disableOriginalConstructor()
			->getMock();

		$infoMock->fieldName = 'sample';
		$infoMock->operation = new stdClass();
		$infoMock->operation->operation = 'query';

		$fieldResolver = new FieldResolversManager();
		$fieldResolver->getFieldResolvers()->add('query', 'sample', SampleFieldResolver::class);

		$result = $fieldResolver(
			null,
			['message' => ['message' => $expectedMessage]],
			null,
			$infoMock
		);

		$promiseAdapter = new SyncPromiseAdapter();
		$promise = $promiseAdapter->convertThenable($result);
		$result = $promiseAdapter->wait($promise);

		$this->assertSame($expectedMessage, $result['message'] ?? '');
	}
}
