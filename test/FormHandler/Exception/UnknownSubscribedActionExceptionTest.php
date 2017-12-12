<?php
/**
 * @copyright 2017 Hostnet B.V.
 */
namespace Hostnet\Component\FormHandler\Exception;

/**
 * @covers \Hostnet\Component\FormHandler\Exception\UnknownSubscribedActionException
 */
class UnknownSubscribedActionExceptionTest extends \PHPUnit_Framework_TestCase
{
    public function test()
    {
        $exception = new UnknownSubscribedActionException('foobar', ['foo', 'bar']);

        self::assertEquals('Unknown subscribed action(s) "foo", "bar" given by "foobar".', $exception->getMessage());
    }
}
