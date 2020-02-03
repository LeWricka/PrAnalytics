<?php

namespace App\Infrastructure\Controller;

use App\Application\Services\AddPRComment\AddPRCommentService;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AddCommentController
{
    /**
     * @param LoggerInterface     $logger
     *
     * @param AddPRCommentService $addPRCommentService
     *
     * @return Response
     */
    public function __invoke(LoggerInterface $logger, AddPRCommentService $addPRCommentService)
    {
        $request = Request::createFromGlobals();
        $addPRCommentService->execute($request->getContent(), $this->transformAction($request));

        return new Response();
    }

    /**
     * @param Request $request
     *
     * @return array
     */
    private function transformAction(Request $request): array
    {
        $parametersAsArray = [];
        if ($request->getContent()) {
            $parametersAsArray = json_decode($request->getContent(), true);
        }
        return $parametersAsArray;
    }
}
