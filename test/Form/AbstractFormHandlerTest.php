<?php
/**
 * @copyright 2017 Hostnet B.V.
 */
declare(strict_types=1);

namespace Hostnet\Component\Form\Simple;

use Hostnet\Component\Form\AbstractFormHandler;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\FormInterface;

/**
 * @covers \Hostnet\Component\Form\AbstractFormHandler
 */
class AbstractFormHandlerTest extends TestCase
{
    private $handler;

    public function setUp(): void
    {
        $this->handler = $this->getMockForAbstractClass(AbstractFormHandler::class);
    }

    public function testGettersAndSetters(): void
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
