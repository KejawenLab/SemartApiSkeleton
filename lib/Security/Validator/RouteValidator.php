<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Security\Validator;

use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

/**
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
final class RouteValidator extends ConstraintValidator
{
    private RouteCollection $routeCollection;

    public function __construct(RouterInterface $router)
    {
        $this->routeCollection = $router->getRouteCollection();
    }

    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof Route) {
            throw new UnexpectedTypeException($constraint, Route::class);
        }

        if (!is_string($value)) {
            throw new UnexpectedValueException($value, 'string');
        }

        if ('#' === $value) {
            return;
        }

        if (!($this->routeCollection->get($value) || $this->routeCollection->get(sprintf('%s__invoke', $value)))) {
            $this->context->buildViolation($constraint->getMessage())->setParameter('[ROUTE]', $value)->addViolation();
        }
    }
}
