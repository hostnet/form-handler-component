<?php
namespace Hostnet\Component\Form\Exception;

/**
 * @author Yannick de Lange <ydelange@hostnet.nl>
 * @coversDefaultClass Hostnet\Component\Form\Exception\FormNotFoundException
 */
class FormNotFoundExceptionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers ::__construct
     */
    public function testConstructor()
    {
        $handler = $this
            ->getMockBuilder('Hostnet\Component\Form\FormHandlerInterface')
            ->getMock();

        $e = new FormNotFoundException($handler);

        $this->assertContains(get_class($handler), $e->getMessage());
    }
}
