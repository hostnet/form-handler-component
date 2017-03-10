<?php
namespace Hostnet\Component\FormHandler;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @covers \Hostnet\Component\FormHandler\FormSubmitProcessor
 */
class FormSubmitProcessorTest extends \PHPUnit_Framework_TestCase
{
    private $form;
    private $on_success;
    private $on_failure;

    /**
     * @var FormSubmitProcessor
     */
    private $form_submit_processor;

    protected function setUp()
    {
        $this->form       = $this->prophesize(FormInterface::class);
        $this->on_success = false;
        $this->on_failure = false;

        $this->form_submit_processor = new FormSubmitProcessor(
            $this->form->reveal(),
            function () {
                $this->on_success = true;

                return 'success';
            },
            function () {
                $this->on_failure = true;

                return 'failure';
            }
        );
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
}
