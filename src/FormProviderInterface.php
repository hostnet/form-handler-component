<?php
namespace Hostnet\Component\Form;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Iltar van der Berg <ivanderberg@hostnet.nl>
 * @author Yannick de Lange <ydelange@hostnet.nl>
 */
interface FormProviderInterface
{
    /**
     * @param Request              $request
     * @param FormHandlerInterface $handler
     * @param FormInterface        $form
     * @param mixed                $data initial data for a form if needed
     */
    public function handle(Request $request, FormHandlerInterface $handler, FormInterface $form = null, $data);
}
