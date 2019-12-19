<?php
/**
 * @copyright 2017 Hostnet B.V.
 */
declare(strict_types=1);

namespace Hostnet\Component\Form;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

interface FormProviderInterface
{
    /**
     * @param Request              $request
     * @param FormHandlerInterface $handler
     * @param FormInterface        $form
     */
    public function handle(Request $request, FormHandlerInterface $handler, FormInterface $form = null);
}
