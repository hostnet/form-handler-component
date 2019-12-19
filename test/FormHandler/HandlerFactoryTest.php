<?php
/**
 * @copyright 2017 Hostnet B.V.
 */
declare(strict_types=1);

namespace Hostnet\Component\FormHandler;

use Hostnet\Component\FormHandler\Fixtures\HandlerType\SimpleFormHandler;
use Hostnet\Component\FormHandler\Fixtures\TestType;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

/**
 * @covers \Hostnet\Component\FormHandler\HandlerFactory
 */
class HandlerFactoryTest extends TestCase
{
    private $form_factory;
    private $registry;

    /**
     * @var HandlerFactory
     */
    private $handler_factory;

    protected function setUp(): void
    {
        $this->form_factory = $this->prophesize(FormFactoryInterface::class);
        $this->registry     = $this->prophesize(HandlerRegistryInterface::class);

        $this->handler_factory = new HandlerFactory(
            $this->form_factory->reveal(),
            $this->registry->reveal()
        );
    }

    public function testCreate(): void
    {
        $form = $this->prophesize(FormInterface::class);

        $this->registry->getType('foobar')->willReturn(new SimpleFormHandler());

        $this->form_factory->create(TestType::class, null, [])->willReturn($form);

        $this->handler_factory->create('foobar');
    }
}
