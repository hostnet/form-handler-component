<?php
namespace Hostnet\Component\Form\Simple;

use Symfony\Component\HttpFoundation\Request;

/**
 * @author Yannick de Lange <ydelange@hostnet.nl>
 * @covers ::__construct
 * @coversDefaultClass Hostnet\Component\Form\Simple\SimpleFormProvider
 */
class SimpleFormProviderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @see PHPUnit_Framework_TestCase::setUp()
     */
    public function setUp()
    {
        $this->handler = $this->getMockForAbstractClass('Hostnet\Component\Form\Simple\FormHandlerMock');
        $this->factory = $this->getMock('Symfony\Component\Form\FormFactoryInterface');
        $this->form    = $this->getMock('Symfony\Component\Form\FormInterface');
    }

    /**
     * @covers ::handle
     */
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

    /**
     * @covers ::handle
     */
    public function testFailure()
    {
        $request = new Request();

        $this->form->expects($this->once())
            ->method('isValid')
            ->willReturn(false);

        $this->form->expects($this->once())
            ->method('isSubmitted')
            ->willReturn(true);

        $this->handler->expects($this->never())
            ->method('onSuccess');

        $this->handler->expects($this->once())
            ->method('onFailure')
            ->with($request)
            ->willReturn('bar');

        $provider = new SimpleFormProvider($this->factory);
        $resp     = $provider->handle($request, $this->handler, $this->form);

        $this->assertEquals('bar', $resp);
    }

    /**
     * @covers ::handle
     */
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

    /**
     * @covers ::handle
     */
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

    /**
     * @covers ::handle
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

    /**
     * @covers ::handle
     */
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

    /**
     * @covers ::handle
     */
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
