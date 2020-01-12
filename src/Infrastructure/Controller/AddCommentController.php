<?php

namespace App\Infrastructure\Controller;

use Symfony\Component\HttpFoundation\Response;

class AddCommentController
{
    public function __invoke()
    {
        echo 'hello Iker';

        return new Response('hiiiii');
    }
}
