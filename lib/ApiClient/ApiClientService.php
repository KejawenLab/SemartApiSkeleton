<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\ApiClient;

use KejawenLab\ApiSkeleton\ApiClient\Model\ApiClientRepositoryInterface;
use KejawenLab\ApiSkeleton\Entity\ApiClient;
use KejawenLab\ApiSkeleton\Pagination\AliasHelper;
use KejawenLab\ApiSkeleton\Service\AbstractService;
use KejawenLab\ApiSkeleton\Service\Model\ServiceInterface;
use KejawenLab\ApiSkeleton\Util\Encryptor;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
final class ApiClientService extends AbstractService implements ServiceInterface
{
    public function __construct(MessageBusInterface $messageBus, ApiClientRepositoryInterface $repository, AliasHelper $aliasHelper)
    {
        parent::__construct($messageBus, $repository, $aliasHelper);
    }

    public function save(object $object): void
    {
        /* @var ApiClient $object */
        $object->setApiKey(sha1(date('YmdHis')));
        $object->setSecretKey(Encryptor::hash($object->getApiKey()));

        parent::save($object);
    }
}
