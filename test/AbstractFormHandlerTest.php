<?php
namespace Hostnet\Component\Form\Simple;

/**
 * @author Yannick de Lange <ydelange@hostnet.nl>
 * @covers Hostnet\Component\Form\AbstractFormHandler
 */
class AbstractFormHandlerTest extends \PHPUnit_Framework_TestCase
{
    private $handler;

    public function setUp()
    {
        $this->handler = $this->getMockForAbstractClass('Hostnet\Component\Form\AbstractFormHandler');
    }

    public function testGettersAndSetters()
    {
        $this->handler
            ->expects($this->any())
            ->method('getType')
            ->willReturn('foobar');

        $form = $this->getMock('Symfony\Component\Form\FormInterface');

        $this->assertEquals(null, $this->handler->getName());
        $this->assertEquals([], $this->handler->getOptions());
        $this->assertSame($form, $this->handler->setForm($form)->getForm());
    }
}
