<?php
/**
 * @copyright 2017 Hostnet B.V.
 */
declare(strict_types=1);

namespace Hostnet\Component\Form;

interface NamedFormHandlerInterface extends FormHandlerInterface
{
    /**
     * Return the name of the form, by default this can be the form type.
     *
     * @return string
     */
    public function getName();
}
