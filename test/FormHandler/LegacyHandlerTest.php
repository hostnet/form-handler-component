<?php
/**
 * @copyright 2017 Hostnet B.V.
 */
namespace Hostnet\Component\FormHandler;

use Hostnet\Component\Form\Simple\SimpleFormProvider;
use Hostnet\Component\FormHandler\Fixtures\Legacy\LegacyFormHandler;
use Hostnet\Component\FormHandler\Fixtures\TestData;
use Hostnet\Component\FormHandler\Fixtures\TestType;
use Prophecy\Argument;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\Test\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @coversNothing
 */
class LegacyHandlerTest extends \PHPUnit_Framework_TestCase
{
    public function testGet()
    {
        $request       = Request::create('/', 'GET');
        $form_factory  = $this->prophesize(FormFactoryInterface::class);
        $form_provider = new SimpleFormProvider($form_factory->reveal());
        $handler       = new LegacyFormHandler();

        $form = $this->prophesize(FormInterface::class);

        $form_factory->create(TestType::class, Argument::type(TestData::class), [])->willReturn($form);

        self::assertNull($form_provider->handle($request, $handler));
    }

    public function testPostSuccess()
    {
        $request       = Request::create('/', 'POST');
        $form_factory  = $this->prophesize(FormFactoryInterface::class);
        $form_provider = new SimpleFormProvider($form_factory->reveal());
        $handler       = new LegacyFormHandler();

        $form = $this->prophesize(FormInterface::class);
        $form->handleRequest($request)->shouldBeCalled();
        $form->isSubmitted()->willReturn(true);
        $form->isValid()->willReturn(true);

        $form_factory->create(TestType::class, Argument::type(TestData::class), [])->willReturn($form);

        $resp = $form_provider->handle($request, $handler);

        self::assertInstanceOf(RedirectResponse::class, $resp);
        self::assertSame('http://success.nl/', $resp->getTargetUrl());
    }

    public function testPostFailure()
    {
        $request       = Request::create('/', 'POST');
        $form_factory  = $this->prophesize(FormFactoryInterface::class);
        $form_provider = new SimpleFormProvider($form_factory->reveal());
        $handler       = new LegacyFormHandler();

        $form = $this->prophesize(FormInterface::class);
        $form->handleRequest($request)->shouldBeCalled();
        $form->isSubmitted()->willReturn(true);
        $form->isValid()->willReturn(false);

        $form_factory->create(TestType::class, Argument::type(TestData::class), [])->willReturn($form);

        $resp = $form_provider->handle($request, $handler);

        self::assertInstanceOf(RedirectResponse::class, $resp);
        self::assertSame('http://failure.nl/', $resp->getTargetUrl());
    }
}
