<?php
/**
 * @copyright 2017 Hostnet B.V.
 */
namespace Hostnet\Component\Form\Simple;

use Hostnet\Component\Form\FormFailureHandlerInterface;
use Hostnet\Component\Form\FormHandlerInterface;
use Hostnet\Component\Form\FormSuccessHandlerInterface;

abstract class AbstractFormHandlerMock implements
    FormHandlerInterface,
    FormSuccessHandlerInterface,
    FormFailureHandlerInterface
{
}
