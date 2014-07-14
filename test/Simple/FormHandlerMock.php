<?php
namespace Hostnet\Component\Form\Simple;

use Hostnet\Component\Form\FormFailureHandlerInterface;
use Hostnet\Component\Form\FormHandlerInterface;
use Hostnet\Component\Form\FormSuccesHandlerInterface;

abstract class FormHandlerMock implements FormHandlerInterface, FormSuccesHandlerInterface,
 FormFailureHandlerInterface
{
}
