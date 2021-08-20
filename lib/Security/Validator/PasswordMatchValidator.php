<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Security\Validator;

use KejawenLab\ApiSkeleton\Security\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class PasswordMatchValidator extends ConstraintValidator
{
    public function __construct(
        private UserPasswordHasherInterface $encoder,
        private TokenStorageInterface $tokenStorage,
        private TranslatorInterface $translator,
    ) {
    }

    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof PasswordMatch) {
            throw new UnexpectedTypeException($constraint, PasswordMatch::class);
        }

        if (!$value) {
            return;
        }

        if (($token = $this->tokenStorage->getToken()) === null) {
            throw new UnexpectedValueException($token, TokenInterface::class);
        }

        $user = $token->getUser();
        if (!$user instanceof User) {
            throw new UnexpectedValueException($token, User::class);
        }

        if (!$this->encoder->isPasswordValid($user, $value)) {
            $this->context->buildViolation($this->translator->trans($constraint->getMessage(), [], 'validators'))->addViolation();
        }
    }
}
