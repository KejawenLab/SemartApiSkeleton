<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Controller\Cron;

use OpenApi\Attributes\Tag;
use OpenApi\Attributes\Response;
use Exception;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\View\View;
use KejawenLab\ApiSkeleton\Cron\CronService;
use KejawenLab\ApiSkeleton\Entity\Cron;
use KejawenLab\ApiSkeleton\Security\Annotation\Permission;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
#[Permission(menu: 'CRON', actions: [Permission::ADD, Permission::EDIT])]
final class Run extends AbstractFOSRestController
{
    public function __construct(
        private readonly CronService $service,
        private readonly KernelInterface $kernel,
        private readonly TranslatorInterface $translator,
    ) {
    }
    /**
     *
     *
     *
     * @throws Exception
     */
    #[Post(data: '/cronjobs/{id}/run', name: Run::class, priority: -17)]
    #[Security(name: 'Bearer')]
    #[Tag(name: 'Cron')]
    #[Response(response: 200, description: 'Job status', content: [new OA\MediaType(mediaType: 'application/json', new OA\Schema(properties: [new OA\Property(property: 'code', type: 'integer'), new OA\Property(property: 'message', type: 'string')]))])]
    public function __invoke(string $id): View
    {
        $cron = $this->service->get($id);
        if (!$cron instanceof Cron) {
            throw new NotFoundHttpException($this->translator->trans('sas.page.cron.not_found', [], 'pages'));
        }

        $application = new Application($this->kernel);
        $application->setAutoExit(false);

        $input = new ArrayInput([
            'command' => 'semart:cron:run',
            'job' => $cron->getId(),
            '--schedule_now' => null,
        ]);

        $return = $application->run($input, new NullOutput());
        $message = 'sas.page.cron.run_success';
        if (0 !== $return) {
            $message = 'sas.page.cron.run_failed';
        }

        return $this->view([
            'code' => $return,
            'message' => $this->translator->trans($message, [], 'pages'),
        ]);
    }
}
