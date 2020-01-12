<?php

namespace App\Infrastructure\Controller;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AddCommentController
{
    public function __invoke(LoggerInterface $logger)
    {
        echo 'hello Iker';
        $logger->info('I just got the logger !!!!!!!');
        $logger->info('I just got the logger !!!!!!!');
        $request = Request::createFromGlobals();
        $logger->info($request->getContent());
        $logger->info($request->getBaseUrl());

        return new Response('hiiiii');
    }
}
