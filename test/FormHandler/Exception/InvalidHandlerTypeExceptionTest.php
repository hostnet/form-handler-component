<?php
/**
 * @copyright 2017 Hostnet B.V.
 */
declare(strict_types=1);

namespace Hostnet\Component\FormHandler\Exception;

use PHPUnit\Framework\TestCase;

/**
 * @covers \Hostnet\Component\FormHandler\Exception\InvalidHandlerTypeException
 */
class InvalidHandlerTypeExceptionTest extends TestCase
{
    public function test(): void
    {
        $exception = new InvalidHandlerTypeException('foobar');

        self::assertEquals('No handler found for class "foobar".', $exception->getMessage());
    }
}
