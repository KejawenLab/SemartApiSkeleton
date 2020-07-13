<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Admin\Controller;

use KejawenLab\ApiSkeleton\Admin\AdminContext;
use Symfony\Component\HttpClient\Exception\ClientException;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
final class ApiCallerController
{
    /**
     * @Route("/call", name="admin_caller")
     */
    public function __invoke(Request $request, string $baseApiUrl)
    {
        $client = HttpClient::create([
            'base_uri' => $baseApiUrl,
        ]);

        if ($id = $request->query->get(AdminContext::ADMIN_ID_KEY)) {
            $url = sprintf('%s/%s/%s', AdminContext::API_PATH_PREFIX, $request->query->get(AdminContext::ADMIN_ACTION_KEY), $id);
        } else {
            $url = sprintf('%s/%s', AdminContext::API_PATH_PREFIX, $request->query->get(AdminContext::ADMIN_ACTION_KEY));
        }

        $options = [];
        $session = $request->getSession();
        if ($session->has(AdminContext::ADMIN_SESSION_KEY)) {
            $options['headers']['Authorization'] = sprintf('Bearer %s', $session->get(AdminContext::ADMIN_SESSION_KEY));
        }
        if (in_array($request->getMethod(), [Request::METHOD_PUT, Request::METHOD_POST])) {
            $options = ['json' => static::normalize($request->request->all())];
        } else {
            $request->query->remove(AdminContext::ADMIN_ID_KEY);
            $request->query->remove(AdminContext::ADMIN_ACTION_KEY);
        }

        try {
            $response = new JsonResponse($this->processResponse($request, $client->request($request->getMethod(), $url, $options)->getContent()));
        } catch (ClientException $e) {
            if ($e->getCode() === Response::HTTP_UNAUTHORIZED) {
                $session->remove(AdminContext::ADMIN_SESSION_KEY);
            }

            $response = new JsonResponse([
                'error' => $e->getCode(),
                'message' => $e->getMessage(),
            ], $e->getCode());
        }

        return $response;
    }

    private static function normalize(array $request): array
    {
        foreach ($request as $key => $value) {
            if ('false' === $value) {
                $request[$key] = false;
            }

            if ('true' === $value) {
                $request[$key] = $value;
            }
        }

        return $request;
    }

    private function processResponse(Request $request, string $response): array
    {
        $response = json_decode($response, true);
        if (array_key_exists(AdminContext::ADMIN_TOKEN_KEY, $response)) {
            $session = $request->getSession();
            $session->set(AdminContext::ADMIN_SESSION_KEY, $response[AdminContext::ADMIN_TOKEN_KEY]);

            $response[AdminContext::ADMIN_TOKEN_KEY] = true;
        }

        return $response;
    }
}
