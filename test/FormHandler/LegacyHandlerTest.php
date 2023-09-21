<?php
/**
 * @copyright 2017 Hostnet B.V.
 */
declare(strict_types=1);

namespace Hostnet\Component\FormHandler;

use Hostnet\Component\Form\Simple\SimpleFormProvider;
use Hostnet\Component\FormHandler\Fixtures\Legacy\LegacyFormHandler;
use Hostnet\Component\FormHandler\Fixtures\TestData;
use Hostnet\Component\FormHandler\Fixtures\TestType;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\Test\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @coversNothing
 */
class LegacyHandlerTest extends TestCase
{
    use ProphecyTrait;

    public function testGet(): void
    {
        $request       = Request::create('/', 'GET');
        $form_factory  = $this->prophesize(FormFactoryInterface::class);
        $form_provider = new SimpleFormProvider($form_factory->reveal());
        $handler       = new LegacyFormHandler();

        $form = $this->prophesize(FormInterface::class);
        $form->handleRequest($request)->shouldBeCalled()->willReturn($form);
        $form->isSubmitted()->willReturn(false);

        $form_factory->create(TestType::class, Argument::type(TestData::class), [])->willReturn($form);

        self::assertNull($form_provider->handle($request, $handler));
    }

    public function testPostSuccess(): void
    {
        $request       = Request::create('/', 'POST');
        $form_factory  = $this->prophesize(FormFactoryInterface::class);
        $form_provider = new SimpleFormProvider($form_factory->reveal());
        $handler       = new LegacyFormHandler();

        $form = $this->prophesize(FormInterface::class);
        $form->handleRequest($request)->shouldBeCalled()->willReturn($form);
        $form->isSubmitted()->willReturn(true);
        $form->isValid()->willReturn(true);

        $form_factory->create(TestType::class, Argument::type(TestData::class), [])->willReturn($form);

        $resp = $form_provider->handle($request, $handler);

        self::assertInstanceOf(RedirectResponse::class, $resp);
        self::assertSame('http://success.nl/', $resp->getTargetUrl());
    }

    public function testPostFailure(): void
    {
        $request       = Request::create('/', 'POST');
        $form_factory  = $this->prophesize(FormFactoryInterface::class);
        $form_provider = new SimpleFormProvider($form_factory->reveal());
        $handler       = new LegacyFormHandler();

        $form = $this->prophesize(FormInterface::class);
        $form->handleRequest($request)->shouldBeCalled()->willReturn($form);
        $form->isSubmitted()->willReturn(true);
        $form->isValid()->willReturn(false);

        $form_factory->create(TestType::class, Argument::type(TestData::class), [])->willReturn($form);

        $resp = $form_provider->handle($request, $handler);

        self::assertInstanceOf(RedirectResponse::class, $resp);
        self::assertSame('http://failure.nl/', $resp->getTargetUrl());
    }
}
