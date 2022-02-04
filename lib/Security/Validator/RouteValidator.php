<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Security\Validator;

use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class RouteValidator extends ConstraintValidator
{
    private readonly RouteCollection $routeCollection;

    public function __construct(RouterInterface $router, private readonly TranslatorInterface $translator)
    {
        $this->routeCollection = $router->getRouteCollection();
    }

    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof Route) {
            throw new UnexpectedTypeException($constraint, Route::class);
        }

        if (!\is_string($value)) {
            throw new UnexpectedValueException($value, 'string');
        }

        if ('#' === $value) {
            return;
        }

        if (null !== $this->routeCollection->get($value)) {
            return;
        }

        if (null !== $this->routeCollection->get(sprintf('%s__invoke', $value))) {
            return;
        }

        $this->context->buildViolation($this->translator->trans($constraint->getMessage(), ['%route%' => $value], 'validators'))->addViolation();
    }
}
