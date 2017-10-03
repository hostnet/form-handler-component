<?php
namespace Hostnet\Component\FormHandler;

use Hostnet\Component\FormHandler\Fixtures\ActionSubscriber\FailureSubscriber;
use Hostnet\Component\FormHandler\Fixtures\ActionSubscriber\HenkSubscriber;
use Hostnet\Component\FormHandler\Fixtures\ActionSubscriber\SuccessSubscriber;
use Hostnet\Component\FormHandler\Fixtures\TestType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @covers \Hostnet\Component\FormHandler\HandlerBuilder
 */
class HandlerBuilderTest extends \PHPUnit_Framework_TestCase
{
    public function testSetName()
    {
        $request      = Request::create('/', 'POST');
        $form_factory = $this->prophesize(FormFactoryInterface::class);

        $form = $this->prophesize(FormInterface::class);
        $form->handleRequest($request)->shouldBeCalled();
        $form->isSubmitted()->willReturn(true);
        $form->isValid()->willReturn(true);
        $form->getData()->willReturn(null);

        $form_factory->createNamed('foobar', TestType::class, null, [])->willReturn($form);

        $builder = new HandlerBuilder();
        $builder->setType(TestType::class);
        $builder->setName('foobar');

        $handler = $builder->build($form_factory->reveal());
        $handler->process($request);
    }

    public function testSetOptions()
    {
        $request      = Request::create('/', 'POST');
        $form_factory = $this->prophesize(FormFactoryInterface::class);

        $form = $this->prophesize(FormInterface::class);
        $form->handleRequest($request)->shouldBeCalled();
        $form->isSubmitted()->willReturn(true);
        $form->isValid()->willReturn(true);
        $form->getData()->willReturn(null);

        $form_factory->create(TestType::class, null, ['foo' => 'bar'])->willReturn($form);

        $builder = new HandlerBuilder();
        $builder->setType(TestType::class);
        $builder->setOptions(['foo' => 'bar']);

        $handler = $builder->build($form_factory->reveal());
        $handler->process($request);
    }

    public function testSetOptionsCallback()
    {
        $request      = Request::create('/', 'POST');
        $form_factory = $this->prophesize(FormFactoryInterface::class);

        $form = $this->prophesize(FormInterface::class);
        $form->handleRequest($request)->shouldBeCalled();
        $form->isSubmitted()->willReturn(true);
        $form->isValid()->willReturn(true);
        $form->getData()->willReturn(null);

        $form_factory->create(TestType::class, null, ['foo' => 'bar'])->willReturn($form);

        $builder = new HandlerBuilder();
        $builder->setType(TestType::class);
        $builder->setOptions(function () {
            return ['foo' => 'bar'];
        });

        $handler = $builder->build($form_factory->reveal());
        $handler->process($request);
    }

    public function testBuildSuccess()
    {
        $spy = new SuccessSubscriber();

        $request      = Request::create('/', 'POST');
        $form_factory = $this->prophesize(FormFactoryInterface::class);

        $form = $this->prophesize(FormInterface::class);
        $form->handleRequest($request)->shouldBeCalled();
        $form->isSubmitted()->willReturn(true);
        $form->isValid()->willReturn(true);
        $form->getData()->willReturn(null);

        $form_factory->create(TestType::class, null, [])->willReturn($form);

        $builder = new HandlerBuilder();
        $builder->registerActionSubscriber($spy);
        $builder->setType(TestType::class);

        $handler = $builder->build($form_factory->reveal());
        $handler->process($request);

        self::assertTrue($spy->success);
        self::assertFalse($spy->failure);
    }

    public function testBuildFailure()
    {
        $spy = new FailureSubscriber();

        $request      = Request::create('/', 'POST');
        $form_factory = $this->prophesize(FormFactoryInterface::class);

        $form = $this->prophesize(FormInterface::class);
        $form->handleRequest($request)->shouldBeCalled();
        $form->isSubmitted()->willReturn(true);
        $form->isValid()->willReturn(false);
        $form->getData()->willReturn(null);

        $form_factory->create(TestType::class, null, [])->willReturn($form);

        $builder = new HandlerBuilder();
        $builder->registerActionSubscriber($spy);
        $builder->setType(TestType::class);

        $handler = $builder->build($form_factory->reveal());
        $handler->process($request);

        self::assertFalse($spy->success);
        self::assertTrue($spy->failure);
    }

    public function testBuildSuccessCallback()
    {
        $success = false;
        $failure = false;

        $request      = Request::create('/', 'POST');
        $form_factory = $this->prophesize(FormFactoryInterface::class);

        $form = $this->prophesize(FormInterface::class);
        $form->handleRequest($request)->shouldBeCalled();
        $form->isSubmitted()->willReturn(true);
        $form->isValid()->willReturn(true);
        $form->getData()->willReturn(null);

        $form_factory->create(TestType::class, null, [])->willReturn($form);

        $builder = new HandlerBuilder();
        $builder->onSuccess(function () use (&$success) {
            $success = true;
        });
        $builder->onFailure(function () use (&$failure) {
            $failure = true;
        });
        $builder->setType(TestType::class);

        $handler = $builder->build($form_factory->reveal());
        $handler->process($request);

        self::assertTrue($success);
        self::assertFalse($failure);
    }

    public function testBuildFailureCallback()
    {
        $success = false;
        $failure = false;

        $request      = Request::create('/', 'POST');
        $form_factory = $this->prophesize(FormFactoryInterface::class);

        $form = $this->prophesize(FormInterface::class);
        $form->handleRequest($request)->shouldBeCalled();
        $form->isSubmitted()->willReturn(true);
        $form->isValid()->willReturn(false);
        $form->getData()->willReturn(null);

        $form_factory->create(TestType::class, null, [])->willReturn($form);

        $builder = new HandlerBuilder();
        $builder->onSuccess(function () use (&$success) {
            $success = true;
        });
        $builder->onFailure(function () use (&$failure) {
            $failure = true;
        });
        $builder->setType(TestType::class);

        $handler = $builder->build($form_factory->reveal());
        $handler->process($request);

        self::assertFalse($success);
        self::assertTrue($failure);
    }

    /**
     * @expectedException \Hostnet\Component\FormHandler\Exception\UnknownSubscribedActionException
     */
    public function testRegisterActionSubscriberWrongAction()
    {
        $builder = new HandlerBuilder();
        $builder->registerActionSubscriber(new HenkSubscriber());
    }

    /**
     * @dataProvider providerBuildInvalidHandlerType
     * @expectedException \Hostnet\Component\FormHandler\Exception\InvalidHandlerTypeException
     */
    public function testBuildInvalidHandlerType($type)
    {
        $success = false;
        $failure = false;

        $request      = Request::create('/', 'POST');
        $form_factory = $this->prophesize(FormFactoryInterface::class);

        $form = $this->prophesize(FormInterface::class);
        $form->handleRequest($request)->shouldNotBeCalled();
        $form->isSubmitted()->willReturn(true);
        $form->isValid()->willReturn(false);
        $form->getData()->willReturn(null);

        $form_factory->create(TestType::class, null, [])->willReturn($form);

        $builder = new HandlerBuilder();
        $builder->onSuccess(function () use (&$success) {
            $success = true;
        });
        $builder->onFailure(function () use (&$failure) {
            $failure = true;
        });

        $builder->setType($type);

        $handler = $builder->build($form_factory->reveal());
        $handler->process($request);

        self::assertFalse($success);
        self::assertTrue($failure);
    }

    public function providerBuildInvalidHandlerType()
    {
        return [
            [600],
            [\Exception::class]
        ];
    }
}
