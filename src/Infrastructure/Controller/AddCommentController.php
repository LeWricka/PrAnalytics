<?php

namespace App\Infrastructure\Controller;

use Elasticsearch\ClientBuilder;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AddCommentController
{
    const COMMENTS_INDEX = 'comments';

    /**
     * @param LoggerInterface $logger
     *
     * @return Response
     */
    public function __invoke(LoggerInterface $logger)
    {
        $request = Request::createFromGlobals();
        try {
            $client = ClientBuilder::create()->setHosts(['elasticsearch:9200'])->build();
            if (!$client->indices()->exists(['index' => self::COMMENTS_INDEX])) {
                $client->indices()->create(['index' => self::COMMENTS_INDEX]);
            }

            $client->index(
                [
                    'index' => self::COMMENTS_INDEX,
                    'type' => 'comment',
                    'body' => $request->getContent()
                ]
            );

        } catch (\Exception $exception) {
            return new Response($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        return new Response();
    }
}
