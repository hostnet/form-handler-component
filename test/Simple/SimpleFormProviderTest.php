<?php
namespace Hostnet\Component\Form\Simple;

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
        $this->handler = $this->getMock('Hostnet\Component\Form\Simple\FormHandlerMock');
        $this->factory = $this->getMock('Symfony\Component\Form\FormFactoryInterface');
        $this->form    = $this->getMock('Symfony\Component\Form\FormInterface');
    }

    public function testSuccess()
    {
        $request = new Request();

        $this->form
            ->expects($this->once())
            ->method('isValid')
            ->willReturn(true);

        $this->form
            ->expects($this->once())
            ->method('isSubmitted')
            ->willReturn(true);

        $this->handler
            ->expects($this->once())
            ->method('onSuccess')
            ->with($request)
            ->willReturn('foo');

        $this->handler
            ->expects($this->never())
            ->method('onFailure');

        $provider = new SimpleFormProvider($this->factory);
        $resp     = $provider->handle($request, $this->handler, $this->form);

        $this->assertEquals('foo', $resp);
    }

    public function testFailure()
    {
        $request = new Request();

        $this->form
            ->expects($this->once())
            ->method('isValid')
            ->willReturn(false);

        $this->form
            ->expects($this->once())
            ->method('isSubmitted')
            ->willReturn(true);

        $this->handler
            ->expects($this->never())
            ->method('onSuccess');

        $this->handler
            ->expects($this->once())
            ->method('onFailure')
            ->with($request)
            ->willReturn('bar');

        $provider = new SimpleFormProvider($this->factory);
        $resp     = $provider->handle($request, $this->handler, $this->form);

        $this->assertEquals('bar', $resp);
    }

    public function testNotSubmitted()
    {
        $this->form
            ->expects($this->once())
            ->method('isSubmitted')
            ->willReturn(false);

        $this->handler
            ->expects($this->never())
            ->method('onSuccess');
        $this->handler
            ->expects($this->never())
            ->method('onFailure');

        $provider = new SimpleFormProvider($this->factory);
        $provider->handle(new Request(), $this->handler, $this->form);
    }

    public function testNoForm()
    {
        $this->handler
            ->expects($this->once())
            ->method('getOptions')
            ->willReturn([]);

        $this->factory
            ->expects($this->once())
            ->method('create')
            ->willReturn($this->form);

        $this->handler
            ->expects($this->once())
            ->method('setForm')
            ->with($this->form);

        $provider = new SimpleFormProvider($this->factory);
        $provider->handle(new Request(), $this->handler);
    }

    public function testNamedForm()
    {
        $named_handler = $this->getMock('Hostnet\Component\Form\NamedFormHandlerInterface');

        $named_handler
            ->expects($this->once())
            ->method('getOptions')
            ->willReturn([]);
        $named_handler
            ->expects($this->once())
            ->method('getName')
            ->willReturn('foobar');

        $this->factory
            ->expects($this->once())
            ->method('createNamed')
            ->willReturn($this->form);

        $named_handler
            ->expects($this->once())
            ->method('setForm')
            ->with($this->form);

        $provider = new SimpleFormProvider($this->factory);
        $provider->handle(new Request(), $named_handler);
    }

    public function testNamedFormWithNoName()
    {
        $named_handler = $this->getMock('Hostnet\Component\Form\NamedFormHandlerInterface');

        $named_handler
            ->expects($this->once())
            ->method('getOptions')
            ->willReturn([]);
        $named_handler
            ->expects($this->once())
            ->method('getName')
            ->willReturn(null);

        $this->factory
            ->expects($this->once())
            ->method('create')
            ->willReturn($this->form);

        $named_handler
            ->expects($this->once())
            ->method('setForm')
            ->with($this->form);

        $provider = new SimpleFormProvider($this->factory);
        $provider->handle(new Request(), $named_handler);
    }

    /**
     * @expectedException Hostnet\Component\Form\Exception\FormNotFoundException
     */
    public function testNoFormNotFound()
    {
        $this->handler
            ->expects($this->once())
            ->method('getOptions')
            ->willReturn([]);

        $provider = new SimpleFormProvider($this->factory);
        $provider->handle(new Request(), $this->handler);
    }

    public function testNoHandler()
    {
        $this->form
            ->expects($this->once())
            ->method('isValid')
            ->willReturn(true);

        $this->form
            ->expects($this->once())
            ->method('isSubmitted')
            ->willReturn(true);

        $provider = new SimpleFormProvider($this->factory);
        $provider->handle(new Request(), $this->handler, $this->form);
    }

    public function testSubmittedWithNoHandlerInterfaces()
    {
        $handler = $this->getMock('Hostnet\Component\Form\FormHandlerInterface');
        $handler
            ->expects($this->never())
            ->method('onSuccess');
        $handler
            ->expects($this->never())
            ->method('onFailure');

        $this->form
            ->expects($this->once())
            ->method('isSubmitted')
            ->willReturn(true);

        $provider = new SimpleFormProvider($this->factory);
        $provider->handle(new Request(), $handler, $this->form);
    }
}
