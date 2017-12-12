<?php
/**
 * @copyright 2017 Hostnet B.V.
 */
namespace Hostnet\Component\FormHandler\Exception;

/**
 * @covers \Hostnet\Component\FormHandler\Exception\InvalidHandlerTypeException
 */
class InvalidHandlerTypeExceptionTest extends \PHPUnit_Framework_TestCase
{
    public function test()
    {
        $exception = new InvalidHandlerTypeException('foobar');

        self::assertEquals('No handler found for class "foobar".', $exception->getMessage());
    }
}
