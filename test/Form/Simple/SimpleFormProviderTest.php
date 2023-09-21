<?php
/**
 * @copyright 2017 Hostnet B.V.
 */
declare(strict_types=1);

namespace Hostnet\Component\Form\Simple;

use Hostnet\Component\Form\Exception\FormNotFoundException;
use Hostnet\Component\Form\FormHandlerInterface;
use Hostnet\Component\Form\NamedFormHandlerInterface;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @covers \Hostnet\Component\Form\Simple\SimpleFormProvider
 */
class SimpleFormProviderTest extends TestCase
{
    private $handler;
    private $factory;
    private $form;

    public function setUp(): void
    {
        $this->handler = $this->createMock(AbstractFormHandlerMock::class);
        $this->factory = $this->createMock(FormFactoryInterface::class);
        $this->form    = $this->createMock(FormInterface::class);
    }

    public function testSuccess(): void
    {
        $request = new Request();

        $this->form
            ->expects(self::once())
            ->method('isValid')
            ->willReturn(true);

        $this->form
            ->expects(self::once())
            ->method('isSubmitted')
            ->willReturn(true);

        $this->handler
            ->expects(self::once())
            ->method('onSuccess')
            ->with($request)
            ->willReturn('foo');

        $this->handler
            ->expects(self::never())
            ->method('onFailure');

        $provider = new SimpleFormProvider($this->factory);
        $resp     = $provider->handle($request, $this->handler, $this->form);

        self::assertEquals('foo', $resp);
    }

    public function testFailure(): void
    {
        $request = new Request();

        $this->form
            ->expects(self::once())
            ->method('isValid')
            ->willReturn(false);

        $this->form
            ->expects(self::once())
            ->method('isSubmitted')
            ->willReturn(true);

        $this->handler
            ->expects(self::never())
            ->method('onSuccess');

        $this->handler
            ->expects(self::once())
            ->method('onFailure')
            ->with($request)
            ->willReturn('bar');

        $provider = new SimpleFormProvider($this->factory);
        $resp     = $provider->handle($request, $this->handler, $this->form);

        self::assertEquals('bar', $resp);
    }

    public function testNotSubmitted(): void
    {
        $this->form
            ->expects(self::once())
            ->method('isSubmitted')
            ->willReturn(false);

        $this->handler
            ->expects(self::never())
            ->method('onSuccess');
        $this->handler
            ->expects(self::never())
            ->method('onFailure');

        $provider = new SimpleFormProvider($this->factory);
        $provider->handle(new Request(), $this->handler, $this->form);
    }

    public function testNoForm(): void
    {
        $this->handler
            ->expects(self::once())
            ->method('getOptions')
            ->willReturn([]);

        $this->factory
            ->expects(self::once())
            ->method('create')
            ->willReturn($this->form);

        $this->handler
            ->expects(self::once())
            ->method('setForm')
            ->with($this->form);

        $this->handler
            ->expects(self::once())
            ->method('getType')
            ->willReturn(FormType::class);

        $provider = new SimpleFormProvider($this->factory);
        $provider->handle(new Request(), $this->handler);
    }

    public function testNamedForm(): void
    {
        $named_handler = $this->createMock(NamedFormHandlerInterface::class);

        $named_handler
            ->expects(self::once())
            ->method('getOptions')
            ->willReturn([]);
        $named_handler
            ->expects(self::once())
            ->method('getName')
            ->willReturn('foobar');

        $this->factory
            ->expects(self::once())
            ->method('createNamed')
            ->willReturn($this->form);

        $named_handler
            ->expects(self::once())
            ->method('setForm')
            ->with($this->form);

        $named_handler
            ->expects(self::once())
            ->method('getType')
            ->willReturn(FormType::class);

        $provider = new SimpleFormProvider($this->factory);
        $provider->handle(new Request(), $named_handler);
    }

    public function testNamedFormWithNoName(): void
    {
        $named_handler = $this->createMock(NamedFormHandlerInterface::class);

        $named_handler
            ->expects(self::once())
            ->method('getOptions')
            ->willReturn([]);
        $named_handler
            ->expects(self::once())
            ->method('getName')
            ->willReturn(null);

        $this->factory
            ->expects(self::once())
            ->method('create')
            ->willReturn($this->form);

        $named_handler
            ->expects(self::once())
            ->method('setForm')
            ->with($this->form);

        $named_handler
            ->expects(self::once())
            ->method('getType')
            ->willReturn(FormType::class);

        $provider = new SimpleFormProvider($this->factory);
        $provider->handle(new Request(), $named_handler);
    }

    public function testNoFormNotFound(): void
    {
        $this->handler
            ->expects(self::once())
            ->method('getOptions')
            ->willReturn([]);

        $this->handler
            ->expects(self::any())
            ->method('getType')
            ->willReturn(FormType::class);

        $this->factory
            ->method("create")
            ->willThrowException(new \InvalidArgumentException());

        $provider = new SimpleFormProvider($this->factory);

        $this->expectException(FormNotFoundException::class);
        $provider->handle(new Request(), $this->handler);
    }

    public function testNoHandler(): void
    {
        $this->form
            ->expects(self::once())
            ->method('isValid')
            ->willReturn(true);

        $this->form
            ->expects(self::once())
            ->method('isSubmitted')
            ->willReturn(true);

        $provider = new SimpleFormProvider($this->factory);
        $provider->handle(new Request(), $this->handler, $this->form);
    }

    public function testSubmittedWithNoHandlerInterfaces(): void
    {
        $handler = $this->createMock(FormHandlerInterface::class);

        $this->form
            ->expects(self::once())
            ->method('isSubmitted')
            ->willReturn(true);

        $provider = new SimpleFormProvider($this->factory);
        $provider->handle(new Request(), $handler, $this->form);
    }
}
