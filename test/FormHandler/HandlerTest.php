<?php
/**
 * @copyright 2017 Hostnet B.V.
 */
declare(strict_types=1);

namespace Hostnet\Component\FormHandler;

use Hostnet\Component\FormHandler\Fixtures\ArrayHandlerRegistry;
use Hostnet\Component\FormHandler\Fixtures\HandlerType\FullFormHandler;
use Hostnet\Component\FormHandler\Fixtures\HandlerType\SimpleFormHandler;
use Hostnet\Component\FormHandler\Fixtures\Legacy\LegacyFormHandler;
use Hostnet\Component\FormHandler\Fixtures\LegacyHandlerRegistry;
use Hostnet\Component\FormHandler\Fixtures\TestData;
use Hostnet\Component\FormHandler\Fixtures\TestType;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\Test\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @covers \Hostnet\Component\FormHandler\Handler
 */
class HandlerTest extends TestCase
{
    public function testGetForm(): void
    {
        $request         = Request::create('/', 'GET');
        $form_factory    = $this->prophesize(FormFactoryInterface::class);
        $handler_factory = new HandlerFactory(
            $form_factory->reveal(),
            new ArrayHandlerRegistry([new SimpleFormHandler()])
        );
        $data            = new TestData();

        $form = $this->prophesize(FormInterface::class);
        $form->handleRequest($request)->shouldBeCalled();
        $form->isSubmitted()->willReturn(false);

        $form_factory->create(TestType::class, Argument::type(TestData::class), [])->willReturn($form);
        $handler = $handler_factory->create(SimpleFormHandler::class);

        self::assertNull($handler->handle($request, $data));
        self::assertSame($form->reveal(), $handler->getForm());
    }

    public function testCall(): void
    {
        $form_factory    = $this->prophesize(FormFactoryInterface::class);
        $handler_factory = new HandlerFactory(
            $form_factory->reveal(),
            new ArrayHandlerRegistry([new SimpleFormHandler()])
        );

        $this->expectException(\BadMethodCallException::class);
        $this->expectExceptionMessage(
            sprintf(
                'Attempted to call an undefined method named "getData" of class "%s".',
                Handler::class
            )
        );
        $handler_factory->create(SimpleFormHandler::class)->getData();
    }

    /**
     * @group legacy
     */
    public function testCallLegacy(): void
    {
        $form_factory    = $this->prophesize(FormFactoryInterface::class);
        $handler_factory = new HandlerFactory(
            $form_factory->reveal(),
            new LegacyHandlerRegistry([new HandlerTypeAdapter(new LegacyFormHandler())])
        );

        self::assertInstanceOf(TestData::class, $handler_factory->create(LegacyFormHandler::class)->getData());
    }

    public function testGetFormWhenNotHandled(): void
    {
        $form_factory    = $this->prophesize(FormFactoryInterface::class);
        $handler_factory = new HandlerFactory(
            $form_factory->reveal(),
            new ArrayHandlerRegistry([new SimpleFormHandler()])
        );

        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage(
            'Cannot retrieve form when it has not been handled'
        );
        $handler_factory->create(SimpleFormHandler::class)->getForm();
    }

    public function testSimple(): void
    {
        $request         = Request::create('/', 'POST');
        $form_factory    = $this->prophesize(FormFactoryInterface::class);
        $handler_factory = new HandlerFactory(
            $form_factory->reveal(),
            new ArrayHandlerRegistry([new SimpleFormHandler()])
        );
        $data            = new TestData();

        $form = $this->prophesize(FormInterface::class);
        $form->handleRequest($request)->shouldBeCalled();
        $form->isSubmitted()->willReturn(true);
        $form->isValid()->willReturn(true);
        $form->getData()->willReturn($data);

        $form_factory->create(TestType::class, Argument::type(TestData::class), [])->willReturn($form);

        $handler = $handler_factory->create(SimpleFormHandler::class);

        self::assertNull($handler->handle($request, $data));
        self::assertSame($form->reveal(), $handler->getForm());
    }

    public function testPostSuccess(): void
    {
        $request         = Request::create('/', 'POST');
        $form_factory    = $this->prophesize(FormFactoryInterface::class);
        $handler_factory = new HandlerFactory(
            $form_factory->reveal(),
            new ArrayHandlerRegistry([new FullFormHandler()])
        );
        $data            = new TestData();

        $form = $this->prophesize(FormInterface::class);
        $form->handleRequest($request)->shouldBeCalled();
        $form->isSubmitted()->willReturn(true);
        $form->isValid()->willReturn(true);
        $form->getData()->willReturn($data);

        $form_factory->create(TestType::class, Argument::type(TestData::class), [])->willReturn($form);

        $handler = $handler_factory->create(FullFormHandler::class);

        $resp = $handler->handle($request, $data);

        self::assertInstanceOf(RedirectResponse::class, $resp);
        self::assertSame('http://success.nl/', $resp->getTargetUrl());
        self::assertSame($form->reveal(), $handler->getForm());
    }

    /**
     * @group legacy
     */
    public function testPostSuccessSyncData(): void
    {
        $request         = Request::create('/', 'POST');
        $form_factory    = $this->prophesize(FormFactoryInterface::class);
        $legacy_handler  = new LegacyFormHandler();
        $handler_factory = new HandlerFactory(
            $form_factory->reveal(),
            new LegacyHandlerRegistry([new HandlerTypeAdapter($legacy_handler)])
        );
        $data            = new TestData();
        $data->test      = 'foobar';

        $form = $this->prophesize(FormInterface::class);
        $form->handleRequest($request)->shouldBeCalled();
        $form->isSubmitted()->willReturn(true);
        $form->isValid()->willReturn(true);
        $form->getData()->willReturn($data);

        $form_factory->create(TestType::class, Argument::type(TestData::class), [])->willReturn($form);

        $handler = $handler_factory->create(LegacyFormHandler::class);

        $resp = $handler->handle($request, $data);

        self::assertInstanceOf(RedirectResponse::class, $resp);
        self::assertSame('http://success.nl/foobar', $resp->getTargetUrl());
        self::assertSame($form->reveal(), $handler->getForm());
        self::assertSame($data->test, $legacy_handler->getData()->test);
    }

    public function testPostFailure(): void
    {
        $request         = Request::create('/', 'POST');
        $form_factory    = $this->prophesize(FormFactoryInterface::class);
        $handler_factory = new HandlerFactory(
            $form_factory->reveal(),
            new ArrayHandlerRegistry([new FullFormHandler()])
        );
        $data            = new TestData();

        $form = $this->prophesize(FormInterface::class);
        $form->handleRequest($request)->shouldBeCalled();
        $form->isSubmitted()->willReturn(true);
        $form->isValid()->willReturn(false);
        $form->getData()->willReturn($data);

        $form_factory->create(TestType::class, Argument::type(TestData::class), [])->willReturn($form);

        $handler = $handler_factory->create(FullFormHandler::class);

        $resp = $handler->handle($request, $data);

        self::assertInstanceOf(RedirectResponse::class, $resp);
        self::assertSame('http://failure.nl/', $resp->getTargetUrl());
        self::assertSame($form->reveal(), $handler->getForm());
    }
}
