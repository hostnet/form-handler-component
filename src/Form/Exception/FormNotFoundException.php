<?php
/**
 * @copyright 2017 Hostnet B.V.
 */
declare(strict_types=1);

namespace Hostnet\Component\Form\Exception;

use Hostnet\Component\Form\FormHandlerInterface;

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
