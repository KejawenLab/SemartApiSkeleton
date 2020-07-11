<?php

declare(strict_types=1);

namespace Alpabit\ApiSkeleton\Security\Validator;

use Alpabit\ApiSkeleton\Security\Service\PasswordHistoryService;
use Alpabit\ApiSkeleton\Security\Service\UserProviderFactory;
use Alpabit\ApiSkeleton\Security\User;
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
final class PasswordHistoryValidator extends ConstraintValidator
{
    private UserPasswordEncoderInterface $encoder;

    private TokenStorageInterface $tokenStorage;

    private PasswordHistoryService $service;

    private UserProviderFactory $userProviderFactory;

    public function __construct(
        UserPasswordEncoderInterface $encoder,
        TokenStorageInterface $tokenStorage,
        PasswordHistoryService $service,
        UserProviderFactory $userProviderFactory
    ) {
        $this->encoder = $encoder;
        $this->tokenStorage = $tokenStorage;
        $this->service = $service;
        $this->userProviderFactory = $userProviderFactory;
    }

    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof PasswordHistory) {
            throw new UnexpectedTypeException($constraint, PasswordHistory::class);
        }

        if (!$token = $this->tokenStorage->getToken()) {
            throw new UnexpectedValueException($token, TokenInterface::class);
        }

        $passwords = $this->service->getPasswords($this->userProviderFactory->getRealUser($token->getUser()));
        $invalid = count($passwords);
        $user = new User();
        foreach ($passwords as $password) {
            $user->setPassword($password->getPassword());
            if (!$this->encoder->isPasswordValid($user, $value)) {
                --$invalid;
            }
        }

        if (0 < $invalid) {
            $this->context->buildViolation($constraint->getMessage())->addViolation();
        }
    }
}
