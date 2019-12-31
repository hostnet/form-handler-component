<?php
/**
 * @copyright 2017 Hostnet B.V.
 */
declare(strict_types=1);

namespace Hostnet\Component\FormHandler;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Implementations of this class provide the basic handling of a form.
 */
interface HandlerInterface
{
    /**
     * Return the form for this handler.
     *
     * @return FormInterface
     * @throws \LogicException thrown when trying to retrieve a form of a non-handled handler.
     */
    public function getForm();

    /**
     * Handle the form based on the request.
     *
     * @param Request $request
     * @param mixed   $data
     * @return mixed
     */
    public function handle(Request $request, $data = null);
}
