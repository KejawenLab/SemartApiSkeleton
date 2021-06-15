<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Form;

use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
final class FormFactory
{
    public function __construct(private FormFactoryInterface $formFactory)
    {
    }

    public function submitRequest(string $formType, Request $request, $data = null): FormInterface
    {
        $form = $this->formFactory->create($formType, $data);
        $form->submit($request->request->all());

        return $form;
    }
}
