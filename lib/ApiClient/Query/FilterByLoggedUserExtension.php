<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\ApiClient\Query;

use Doctrine\ORM\QueryBuilder;
use KejawenLab\ApiSkeleton\ApiClient\Model\ApiClientInterface;
use KejawenLab\ApiSkeleton\Pagination\AliasHelper;
use KejawenLab\ApiSkeleton\Pagination\Query\AbstractQueryExtension;
use KejawenLab\ApiSkeleton\Security\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class FilterByLoggedUserExtension extends AbstractQueryExtension
{
    public function __construct(AliasHelper $aliasHelper, private TokenStorageInterface $tokenStorage)
    {
        $this->aliasHelper = $aliasHelper;
    }

    public function apply(QueryBuilder $queryBuilder, Request $request): void
    {
        $token = $this->tokenStorage->getToken();
        if (!$token instanceof TokenInterface) {
            return;
        }

        $user = $token->getUser();
        if (!$user instanceof User) {
            return;
        }

        $queryBuilder->andWhere(
            $queryBuilder->expr()->eq(
                sprintf('%s.user', $this->aliasHelper->findAlias('root')),
                $queryBuilder->expr()->literal($user->getId())
            )
        );
    }

    public function support(string $class, Request $request): bool
    {
        return in_array(ApiClientInterface::class, class_implements($class)) && str_contains($request->getPathInfo(), '/me/');
    }
}
