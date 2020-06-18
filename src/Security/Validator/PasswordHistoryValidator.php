<?php

declare(strict_types=1);

namespace Alpabit\ApiSkeleton\Security\Validator;

use Alpabit\ApiSkeleton\Entity\User;
use Alpabit\ApiSkeleton\Security\Service\PasswordHistoryService;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
final class PasswordHistoryValidator extends ConstraintValidator
{
    private UserPasswordEncoderInterface $encoder;

    private TokenStorageInterface $tokenStorage;

    private PasswordHistoryService $service;

    public function __construct(UserPasswordEncoderInterface $encoder, TokenStorageInterface $tokenStorage, PasswordHistoryService $service)
    {
        $this->encoder = $encoder;
        $this->tokenStorage = $tokenStorage;
        $this->service = $service;
    }

    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof PasswordHistory) {
            throw new UnexpectedTypeException($constraint, PasswordHistory::class);
        }

        if (!$token = $this->tokenStorage->getToken()) {
            throw new UnexpectedValueException($token, TokenInterface::class);
        }

        $passwords = $this->service->getPasswords($token->getUser());
        $invalid = count($passwords);
        $user = new User();
        foreach ($passwords as $password) {
            $user->setPassword($password->getPassword());
            if (!$this->encoder->isPasswordValid($user, $value)) {
                $invalid--;
            }
        }

        if (0 < $invalid) {
            $this->context->buildViolation($constraint->getMessage())->addViolation();
        }
    }
}
