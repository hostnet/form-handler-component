<?php
namespace Hostnet\Component\Form\Simple;

/**
 * @author Yannick de Lange <ydelange@hostnet.nl>
 * @coversDefaultClass Hostnet\Component\Form\Simple\SimpleFormProvider
 */
class SimpleFormProviderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers ::__construct
     * @covers ::handle
     */
    public function testSuccess()
    {
        $handler = $this->getMockForAbstractClass('Hostnet\Component\Form\Simple\FormHandlerMock');


    }
}
