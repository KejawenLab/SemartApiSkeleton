<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\ApiClient\Query;

use Doctrine\ORM\QueryBuilder;
use KejawenLab\ApiSkeleton\Admin\AdminContext;
use KejawenLab\ApiSkeleton\ApiClient\Model\ApiClientInterface;
use KejawenLab\ApiSkeleton\Pagination\AliasHelper;
use KejawenLab\ApiSkeleton\Pagination\Query\AbstractQueryExtension;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
final class FilterByUserExtension extends AbstractQueryExtension
{
    public function __construct(private TokenStorageInterface $tokenStorage, AliasHelper $aliasHelper)
    {
        parent::__construct($aliasHelper);
    }

    public function apply(QueryBuilder $queryBuilder, Request $request): void
    {
        if (!$token = $this->tokenStorage->getToken()) {
            return;
        }

        if (!$user = $token->getUser()) {
            return;
        }

        $queryBuilder->andWhere($queryBuilder->expr()->eq(sprintf('%s.user', $this->aliasHelper->findAlias('root')), $queryBuilder->expr()->literal($user->getId())));
    }

    public function support(string $class, Request $request): bool
    {
        return in_array(ApiClientInterface::class, class_implements($class)) && AdminContext::isAdminContext($request);
    }
}
