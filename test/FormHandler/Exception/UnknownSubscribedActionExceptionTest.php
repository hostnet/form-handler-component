<?php
/**
 * @copyright 2017 Hostnet B.V.
 */
declare(strict_types=1);

namespace Hostnet\Component\FormHandler\Exception;

use PHPUnit\Framework\TestCase;

/**
 * @covers \Hostnet\Component\FormHandler\Exception\UnknownSubscribedActionException
 */
class UnknownSubscribedActionExceptionTest extends TestCase
{
    public function test(): void
    {
        $exception = new UnknownSubscribedActionException('foobar', ['foo', 'bar']);

        self::assertEquals('Unknown subscribed action(s) "foo", "bar" given by "foobar".', $exception->getMessage());
    }
}
