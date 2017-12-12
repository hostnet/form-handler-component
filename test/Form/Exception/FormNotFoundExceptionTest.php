<?php
/**
 * @copyright 2017 Hostnet B.V.
 */
namespace Hostnet\Component\Form\Exception;

use Hostnet\Component\Form\FormHandlerInterface;

/**
 * @author Yannick de Lange <ydelange@hostnet.nl>
 * @covers Hostnet\Component\Form\Exception\FormNotFoundException
 */
class FormNotFoundExceptionTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructor()
    {
        $handler = $this->createMock(FormHandlerInterface::class);

        $e = new FormNotFoundException($handler);

        self::assertContains(get_class($handler), $e->getMessage());
    }
}
