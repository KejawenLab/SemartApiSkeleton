<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Client;

use KejawenLab\ApiSkeleton\Client\Model\ClientRepositoryInterface;
use KejawenLab\ApiSkeleton\Entity\Client;
use KejawenLab\ApiSkeleton\Pagination\AliasHelper;
use KejawenLab\ApiSkeleton\Service\AbstractService;
use KejawenLab\ApiSkeleton\Service\Model\ServiceInterface;
use KejawenLab\ApiSkeleton\Util\Encryptor;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
final class ClientService extends AbstractService implements ServiceInterface
{
    public function __construct(MessageBusInterface $messageBus, ClientRepositoryInterface $repository, AliasHelper $aliasHelper)
    {
        parent::__construct($messageBus, $repository, $aliasHelper);
    }

    public function save(object $object): void
    {
        /* @var Client $object */
        $object->setApiKey(sha1(date('Y-m-d H:i:s')));
        $object->setSecretKey(Encryptor::hash($object->getApiKey()));

        parent::save($object);
    }
}
