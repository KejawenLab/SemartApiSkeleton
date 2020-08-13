<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Security\Validator;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

/**
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
final class PasswordMatchValidator extends ConstraintValidator
{
    private UserPasswordEncoderInterface $encoder;

    private TokenStorageInterface $tokenStorage;

    public function __construct(UserPasswordEncoderInterface $encoder, TokenStorageInterface $tokenStorage)
    {
        $this->encoder = $encoder;
        $this->tokenStorage = $tokenStorage;
    }

    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof PasswordMatch) {
            throw new UnexpectedTypeException($constraint, PasswordMatch::class);
        }

        if (!$value) {
            return;
        }

        if (!$token = $this->tokenStorage->getToken()) {
            throw new UnexpectedValueException($token, TokenInterface::class);
        }

        if (!$this->encoder->isPasswordValid($token->getUser(), $value)) {
            $this->context->buildViolation($constraint->getMessage())->addViolation();
        }
    }
}
