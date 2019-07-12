<?php
/**
 * @copyright 2017 Hostnet B.V.
 */
namespace Hostnet\Component\Form;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Iltar van der Berg <ivanderberg@hostnet.nl>
 * @author Yannick de Lange <ydelange@hostnet.nl>
 *
 * @deprecated Use the HandlerConfigInterface instead. Will be removed in version 2.0.
 */
interface FormProviderInterface
{
    /**
     * @param Request              $request
     * @param FormHandlerInterface $handler
     * @param FormInterface        $form
     */
    public function handle(Request $request, FormHandlerInterface $handler, FormInterface $form = null);
}
