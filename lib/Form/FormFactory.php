<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Form;

use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class FormFactory
{
    public function __construct(private FormFactoryInterface $formFactory)
    {
    }

    public function submitRequest(string $formType, Request $request, $data = null): FormInterface
    {
        $form = $this->formFactory->create($formType, $data);
        if ('application/json' === $request->getContentType()) {
            $data = json_decode($request->getContent(), true);
        } else {
            $data = $request->request->all();
        }

        $form->submit($data);

        return $form;
    }
}
