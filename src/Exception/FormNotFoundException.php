<?php
namespace Hostnet\Component\Form\Exception;

use Hostnet\Component\Form\FormHandlerInterface;

/**
 * @author Yannick de Lange <ydelange@hostnet.nl>
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
