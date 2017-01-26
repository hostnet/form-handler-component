<?php
namespace Hostnet\Component\Form\Simple;

use Hostnet\Component\Form\AbstractFormHandler;
use Symfony\Component\Form\FormInterface;

/**
 * @author Yannick de Lange <ydelange@hostnet.nl>
 * @covers Hostnet\Component\Form\AbstractFormHandler
 */
class AbstractFormHandlerTest extends \PHPUnit_Framework_TestCase
{
    private $handler;

    public function setUp()
    {
        $this->handler = $this->getMockForAbstractClass(AbstractFormHandler::class);
    }

    public function testGettersAndSetters()
    {
        $this->handler
            ->expects(self::any())
            ->method('getType')
            ->willReturn('foobar');

        $form = $this->createMock(FormInterface::class);

        self::assertEquals(null, $this->handler->getName());
        self::assertEquals([], $this->handler->getOptions());
        self::assertSame($form, $this->handler->setForm($form)->getForm());
    }
}
