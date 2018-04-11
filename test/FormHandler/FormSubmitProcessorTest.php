<?php
/**
 * @copyright 2017 Hostnet B.V.
 */
namespace Hostnet\Component\FormHandler;

use Prophecy\Argument;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @covers \Hostnet\Component\FormHandler\FormSubmitProcessor
 */
class FormSubmitProcessorTest extends \PHPUnit_Framework_TestCase
{
    private $form;
    private $on_process;

    /**
     * @var FormSubmitProcessor
     */
    private $form_submit_processor;

    protected function setUp()
    {
        $this->form       = $this->prophesize(FormInterface::class);
        $this->on_process = false;

        $this->form_submit_processor = new FormSubmitProcessor(
            $this->form->reveal(),
            function () {
                return 'success';
            },
            function () {
                return 'failure';
            }
        );
    }

    public function callbackFailure()
    {
        return 'failure';
    }

    public function callbackSuccess()
    {
        return 'success';
    }

    public function callbackProcess()
    {
        $this->on_process = true;
    }

    public function testFailureWithArrayCallables()
    {
        $processor = new FormSubmitProcessor(
            $this->form->reveal(),
            [$this, 'callbackSuccess'],
            [$this, 'callbackFailure']
        );

        $request = Request::create('/', 'POST');

        $this->form->handleRequest($request)->shouldBeCalled();
        $this->form->isSubmitted()->willReturn(true);
        $this->form->isValid()->willReturn(false);
        $this->form->getData()->willReturn([]);

        self::assertSame('failure', $processor->process($request));
    }

    public function testSuccessWithArrayCallables()
    {
        $processor = new FormSubmitProcessor(
            $this->form->reveal(),
            [$this, 'callbackSuccess'],
            [$this, 'callbackFailure']
        );

        $request = Request::create('/', 'POST');

        $this->form->handleRequest($request)->shouldBeCalled();
        $this->form->isSubmitted()->willReturn(true);
        $this->form->isValid()->willReturn(true);
        $this->form->getData()->willReturn([]);

        self::assertSame('success', $processor->process($request));
    }

    public function testProcessorWithArray()
    {
        $processor = new FormSubmitProcessor(
            $this->form->reveal(),
            [$this, 'callbackSuccess'],
            [$this, 'callbackFailure'],
            [$this, 'callbackProcess']
        );

        $request = Request::create('/', 'POST');

        $this->form->isSubmitted()->willReturn(true);
        $this->form->isValid()->willReturn(true);
        $this->form->getData()->willReturn([]);

        self::assertSame('success', $processor->process($request));
        self::assertTrue($this->on_process);
    }

    public function testGetForm()
    {
        self::assertSame($this->form->reveal(), $this->form_submit_processor->getForm());
    }

    public function testSubmitNotSubmitted()
    {
        $request = Request::create('/', 'POST');

        $this->form->handleRequest($request)->shouldBeCalled();
        $this->form->isSubmitted()->willReturn(false);

        self::assertNull($this->form_submit_processor->process($request));
    }

    public function testSubmitValid()
    {
        $request = Request::create('/', 'POST');

        $this->form->handleRequest($request)->shouldBeCalled();
        $this->form->isSubmitted()->willReturn(true);
        $this->form->isValid()->willReturn(true);
        $this->form->getData()->willReturn([]);

        self::assertSame('success', $this->form_submit_processor->process($request));
    }

    public function testSubmitValidNoHandler()
    {
        $request               = Request::create('/', 'POST');
        $form_submit_processor = new FormSubmitProcessor($this->form->reveal());

        $this->form->handleRequest($request)->shouldBeCalled();
        $this->form->isSubmitted()->willReturn(true);
        $this->form->isValid()->willReturn(true);
        $this->form->getData()->willReturn([]);

        self::assertNull($form_submit_processor->process($request));
    }

    public function testSubmitInvalid()
    {
        $request = Request::create('/', 'POST');

        $this->form->handleRequest($request)->shouldBeCalled();
        $this->form->isSubmitted()->willReturn(true);
        $this->form->isValid()->willReturn(false);
        $this->form->getData()->willReturn([]);

        self::assertSame('failure', $this->form_submit_processor->process($request));
    }

    public function testSubmitInvalidNoHandler()
    {
        $request               = Request::create('/', 'POST');
        $form_submit_processor = new FormSubmitProcessor($this->form->reveal());

        $this->form->handleRequest($request)->shouldBeCalled();
        $this->form->isSubmitted()->willReturn(true);
        $this->form->isValid()->willReturn(false);
        $this->form->getData()->willReturn([]);

        self::assertNull($form_submit_processor->process($request));
    }

    public function testProcess()
    {
        $this->form->handleRequest(Argument::cetera())->shouldNotBeCalled();
        $this->form->isSubmitted()->willReturn(false);

        $form_submit_processor = new FormSubmitProcessor(
            $this->form->reveal(),
            null,
            null,
            function () {
                $this->on_process = true;
            }
        );

        self::assertNull($form_submit_processor->process(Request::create('/', 'POST')));
        self::assertTrue($this->on_process);
    }
}
