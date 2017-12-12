<?php
/**
 * @copyright 2017 Hostnet B.V.
 */
namespace Hostnet\Component\Form\Simple;

use Hostnet\Component\Form\FormHandlerInterface;
use Hostnet\Component\Form\NamedFormHandlerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Yannick de Lange <ydelange@hostnet.nl>
 * @covers Hostnet\Component\Form\Simple\SimpleFormProvider
 */
class SimpleFormProviderTest extends \PHPUnit_Framework_TestCase
{
    private $handler;
    private $factory;
    private $form;

    public function setUp()
    {
        $this->handler = $this->createMock(AbstractFormHandlerMock::class);
        $this->factory = $this->createMock(FormFactoryInterface::class);
        $this->form    = $this->createMock(FormInterface::class);
    }

    public function testSuccess()
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

    public function testFailure()
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

    public function testNotSubmitted()
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

    public function testNoForm()
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

        $provider = new SimpleFormProvider($this->factory);
        $provider->handle(new Request(), $this->handler);
    }

    public function testNamedForm()
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

        $provider = new SimpleFormProvider($this->factory);
        $provider->handle(new Request(), $named_handler);
    }

    public function testNamedFormWithNoName()
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

        $provider = new SimpleFormProvider($this->factory);
        $provider->handle(new Request(), $named_handler);
    }

    /**
     * @expectedException \Hostnet\Component\Form\Exception\FormNotFoundException
     */
    public function testNoFormNotFound()
    {
        $this->handler
            ->expects(self::once())
            ->method('getOptions')
            ->willReturn([]);

        $provider = new SimpleFormProvider($this->factory);
        $provider->handle(new Request(), $this->handler);
    }

    public function testNoHandler()
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

    public function testSubmittedWithNoHandlerInterfaces()
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
