<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Security\Validator;

use KejawenLab\ApiSkeleton\Security\Model\UserInterface;
use KejawenLab\ApiSkeleton\Security\Service\PasswordHistoryService;
use KejawenLab\ApiSkeleton\Security\Service\UserProviderFactory;
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
final class PasswordHistoryValidator extends ConstraintValidator
{
    public function __construct(
        private UserPasswordHasherInterface $encoder,
        private TokenStorageInterface $tokenStorage,
        private PasswordHistoryService $service,
        private UserProviderFactory $userProviderFactory,
        private TranslatorInterface $translator,
    ) {
    }

    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof PasswordHistory) {
            throw new UnexpectedTypeException($constraint, PasswordHistory::class);
        }

        if (!$value) {
            return;
        }

        $token = $this->tokenStorage->getToken();
        if (null === $token) {
            throw new UnexpectedValueException($token, TokenInterface::class);
        }

        $user = $token->getUser();
        if (!$user instanceof User) {
            throw new UnexpectedValueException($user, User::class);
        }

        $object = $this->userProviderFactory->getRealUser($user);
        if (!$object instanceof UserInterface) {
            throw new UnexpectedValueException($token, UserInterface::class);
        }

        $user = new User();
        foreach ($this->service->getPasswords($object) as $password) {
            $user->setPassword($password->getPassword());
            if ($this->encoder->isPasswordValid($user, $value)) {
                $this->context->buildViolation($this->translator->trans($constraint->getMessage(), [], 'validators'))->addViolation();

                break;
            }
        }
    }
}
