<?php
/**
 * @copyright 2017 Hostnet B.V.
 */
namespace Hostnet\Component\Form\Exception;

use Hostnet\Component\Form\FormHandlerInterface;

/**
 * @author Yannick de Lange <ydelange@hostnet.nl>
 *
 * @deprecated Use the class InvalidHandlerTypeException instead. Will be removed in version 2.0.
 */
class FormNotFoundException extends \RuntimeException
{
    /**
     * @param FormHandlerInterface $handler
     */
    public function __construct(FormHandlerInterface $handler)
    {
        parent::__construct(sprintf(
            "Could not find form for handler %s. Did you forget to set the form?",
            get_class($handler)
        ));
    }
}
