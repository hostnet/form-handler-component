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
        $factory = $this->getMockBuilder('Symfony\Component\Form\FormFactoryInterface')->getMock();
        $form    = $this->getMockBuilder('Symfony\Component\Form\FormInterface')->getMock();
        $request = $this->getMockBuilder('Symfony\Component\HttpFoundation\Request')->getMock();

        $form->expects($this->once())
            ->method('isValid')
            ->willReturn(true);

        $form->expects($this->once())
            ->method('isSubmitted')
            ->willReturn(true);

        $handler->expects($this->once())
            ->method('onSuccess')
            ->with($request)
            ->willReturn("foo");

        $handler->expects($this->never())
            ->method('onFailure');

        $provider = new SimpleFormProvider($factory);
        $resp     = $provider->handle($request, $handler, $form);

        $this->assertEquals("foo", $resp);
    }
    /**
     * @covers ::__construct
     * @covers ::handle
     */
    public function testFailure()
    {
        $handler = $this->getMockForAbstractClass('Hostnet\Component\Form\Simple\FormHandlerMock');
        $factory = $this->getMockBuilder('Symfony\Component\Form\FormFactoryInterface')->getMock();
        $form    = $this->getMockBuilder('Symfony\Component\Form\FormInterface')->getMock();
        $request = $this->getMockBuilder('Symfony\Component\HttpFoundation\Request')->getMock();

        $form->expects($this->once())
            ->method('isValid')
            ->willReturn(false);

        $form->expects($this->once())
            ->method('isSubmitted')
            ->willReturn(true);

        $handler->expects($this->never())
            ->method('onSuccess');

        $handler->expects($this->once())
            ->method('onFailure')
            ->with($request)
            ->willReturn("bar");

        $provider = new SimpleFormProvider($factory);
        $resp     = $provider->handle($request, $handler, $form);

        $this->assertEquals("bar", $resp);
    }

    /**
     * @covers ::__construct
     * @covers ::handle
     */
    public function testNotSubmitted()
    {
        $handler = $this->getMockForAbstractClass('Hostnet\Component\Form\Simple\FormHandlerMock');
        $factory = $this->getMockBuilder('Symfony\Component\Form\FormFactoryInterface')->getMock();
        $form    = $this->getMockBuilder('Symfony\Component\Form\FormInterface')->getMock();
        $request = $this->getMockBuilder('Symfony\Component\HttpFoundation\Request')->getMock();

        $form->expects($this->once())
            ->method('isSubmitted')
            ->willReturn(false);

        $handler->expects($this->never())
            ->method('onSuccess');
        $handler->expects($this->never())
            ->method('onFailure');

        $provider = new SimpleFormProvider($factory);
        $provider->handle($request, $handler, $form);
    }

    /**
     * @covers ::__construct
     * @covers ::handle
     */
    public function testNoForm()
    {
        $handler = $this->getMockForAbstractClass('Hostnet\Component\Form\Simple\FormHandlerMock');
        $factory = $this->getMockBuilder('Symfony\Component\Form\FormFactoryInterface')->getMock();
        $request = $this->getMockBuilder('Symfony\Component\HttpFoundation\Request')->getMock();
        $form    = $this->getMockBuilder('Symfony\Component\Form\FormInterface')->getMock();

        $handler
            ->expects($this->once())
            ->method("getOptions")
            ->willReturn([]);

        $factory
            ->expects($this->once())
            ->method("create")
            ->willReturn($form);

        $handler
            ->expects($this->once())
            ->method("setForm")
            ->with($form);

        $provider = new SimpleFormProvider($factory);
        $provider->handle($request, $handler);
    }

    /**
     * @covers ::__construct
     * @covers ::handle
     * @expectedException Hostnet\Component\Form\Exception\FormNotFoundException
     */
    public function testNoFormNotFound()
    {
        $handler = $this->getMockForAbstractClass('Hostnet\Component\Form\Simple\FormHandlerMock');
        $factory = $this->getMockBuilder('Symfony\Component\Form\FormFactoryInterface')->getMock();
        $request = $this->getMockBuilder('Symfony\Component\HttpFoundation\Request')->getMock();

        $handler
            ->expects($this->once())
            ->method("getOptions")
            ->willReturn([]);

        $provider = new SimpleFormProvider($factory);
        $provider->handle($request, $handler);
    }

    /**
     * @covers ::__construct
     * @covers ::handle
     */
    public function testNoHandler()
    {
        $handler = $this->getMockBuilder('Hostnet\Component\Form\FormHandlerInterface')->getMock();
        $factory = $this->getMockBuilder('Symfony\Component\Form\FormFactoryInterface')->getMock();
        $form    = $this->getMockBuilder('Symfony\Component\Form\FormInterface')->getMock();
        $request = $this->getMockBuilder('Symfony\Component\HttpFoundation\Request')->getMock();

        $form->expects($this->once())
            ->method('isValid')
            ->willReturn(true);

        $form->expects($this->once())
            ->method('isSubmitted')
            ->willReturn(true);

        $provider = new SimpleFormProvider($factory);
        $provider->handle($request, $handler, $form);
    }
}
