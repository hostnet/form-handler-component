<?php
namespace Hostnet\Component\FormHandler;

use Hostnet\Component\FormHandler\Fixtures\Legacy\LegacyFormHandler;
use Hostnet\Component\FormHandler\Fixtures\Legacy\LegacyFormVariableOptionsHandler;
use Hostnet\Component\FormHandler\Fixtures\LegacyHandlerRegistry;
use Hostnet\Component\FormHandler\Fixtures\TestData;
use Hostnet\Component\FormHandler\Fixtures\TestType;
use Prophecy\Argument;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\Test\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @group legacy
 * @coversNothing
 */
class HandlerBackwardsCompatibilityTest extends \PHPUnit_Framework_TestCase
{
    public function testGet()
    {
        $request         = Request::create('/', 'GET');
        $form_factory    = $this->prophesize(FormFactoryInterface::class);
        $handler_factory = new HandlerFactory(
            $form_factory->reveal(),
            new LegacyHandlerRegistry([new HandlerTypeAdapter(new LegacyFormHandler())])
        );
        $data            = new TestData();
        $data->test      = 'foobar';

        $form = $this->prophesize(FormInterface::class);

        $form_factory->create(TestType::class, Argument::type(TestData::class), [])->willReturn($form);

        self::assertNull($handler_factory->create(LegacyFormHandler::class)->handle($request, $data));
    }

    public function testGetOptions()
    {
        $request         = Request::create('/', 'GET');
        $form_factory    = $this->prophesize(FormFactoryInterface::class);
        $handler_factory = new HandlerFactory(
            $form_factory->reveal(),
            new LegacyHandlerRegistry([new HandlerTypeAdapter(new LegacyFormVariableOptionsHandler())])
        );
        $data            = new TestData();
        $data->test      = 'foobar';

        $form = $this->prophesize(FormInterface::class);

        $form_factory
            ->create(TestType::class, Argument::type(TestData::class), ['attr' => ['class' => 'foobar']])
            ->willReturn($form);

        self::assertNull($handler_factory->create(LegacyFormVariableOptionsHandler::class)->handle($request, $data));
    }

    public function testGetData()
    {
        $form_factory    = $this->prophesize(FormFactoryInterface::class);
        $handler_factory = new HandlerFactory(
            $form_factory->reveal(),
            new LegacyHandlerRegistry([new HandlerTypeAdapter(new LegacyFormVariableOptionsHandler())])
        );

        $handler = $handler_factory->create(LegacyFormVariableOptionsHandler::class);

        self::assertInstanceOf(TestData::class, $handler->getData());
    }

    public function testPostSuccess()
    {
        $request         = Request::create('/', 'POST');
        $form_factory    = $this->prophesize(FormFactoryInterface::class);
        $handler_factory = new HandlerFactory(
            $form_factory->reveal(),
            new LegacyHandlerRegistry([new HandlerTypeAdapter(new LegacyFormHandler())])
        );
        $data            = new TestData();
        $data->test      = 'foobar';

        $form = $this->prophesize(FormInterface::class);
        $form->handleRequest($request)->shouldBeCalled();
        $form->isSubmitted()->willReturn(true);
        $form->isValid()->willReturn(true);
        $form->getData()->willReturn($data);

        $form_factory->create(TestType::class, Argument::type(TestData::class), [])->willReturn($form);

        $resp = $handler_factory->create(LegacyFormHandler::class)->handle($request, $data);

        self::assertInstanceOf(RedirectResponse::class, $resp);
        self::assertSame('http://success.nl/foobar', $resp->getTargetUrl());
    }

    public function testPostFailure()
    {
        $request         = Request::create('/', 'POST');
        $form_factory    = $this->prophesize(FormFactoryInterface::class);
        $handler_factory = new HandlerFactory(
            $form_factory->reveal(),
            new LegacyHandlerRegistry([new HandlerTypeAdapter(new LegacyFormHandler())])
        );
        $data            = new TestData();
        $data->test      = 'foobar';

        $form = $this->prophesize(FormInterface::class);
        $form->handleRequest($request)->shouldBeCalled();
        $form->isSubmitted()->willReturn(true);
        $form->isValid()->willReturn(false);
        $form->getData()->willReturn($data);

        $form_factory->create(TestType::class, Argument::type(TestData::class), [])->willReturn($form);

        $resp = $handler_factory->create(LegacyFormHandler::class)->handle($request, $data);

        self::assertInstanceOf(RedirectResponse::class, $resp);
        self::assertSame('http://failure.nl/foobar', $resp->getTargetUrl());
    }
}
