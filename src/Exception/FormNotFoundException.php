<?php
namespace Hostnet\Form\Exception;

use Hostnet\Component\Form\FormInformationInterface;

/**
 * @author Yannick de Lange <ydelange@hostnet.nl>
 */
class FormNotFoundException extends \RuntimeException
{
    /**
     * @param FormInformationInterface $handler
     */
    public function __construct(FormInformationInterface $handler)
    {
        parent::__construct(sprintf(
            "Could not find form for handler %s. Did you forget to set the form?",
            get_class($handler)
        ));
    }
}
