<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\ApiClient;

use KejawenLab\ApiSkeleton\ApiClient\Model\ApiClientInterface;
use KejawenLab\ApiSkeleton\ApiClient\Model\ApiClientRepositoryInterface;
use KejawenLab\ApiSkeleton\Entity\ApiClient;
use KejawenLab\ApiSkeleton\Pagination\AliasHelper;
use KejawenLab\ApiSkeleton\Security\Model\UserInterface;
use KejawenLab\ApiSkeleton\Service\AbstractService;
use KejawenLab\ApiSkeleton\Service\Model\ServiceInterface;
use KejawenLab\ApiSkeleton\Util\Encryptor;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class ApiClientService extends AbstractService implements ServiceInterface
{
    public function __construct(MessageBusInterface $messageBus, ApiClientRepositoryInterface $repository, AliasHelper $aliasHelper)
    {
        parent::__construct($messageBus, $repository, $aliasHelper);
    }

    /**
     * @param ApiClient $object
     */
    #[\Override]
    public function save(object $object): void
    {
        $object->setApiKey(sha1(date('YmdHis')));
        $object->setSecretKey(Encryptor::hash($object->getApiKey()));

        parent::save($object);
    }

    public function countByUser(UserInterface $user): int
    {
        return $this->repository->countByUser($user);
    }

    public function getByIdAndUser(string $id, UserInterface $user): ?ApiClientInterface
    {
        return $this->repository->findByIdAndUser($id, $user);
    }
}
