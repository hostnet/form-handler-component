<?php
namespace Hostnet\Component\Form\Simple;

use Hostnet\Component\Form\FormFailureHandlerInterface;
use Hostnet\Component\Form\FormInformationInterface;
use Hostnet\Component\Form\FormSuccesHandlerInterface;

abstract class FormHandlerMock implements FormInformationInterface, FormSuccesHandlerInterface,
 FormFailureHandlerInterface
{
}
