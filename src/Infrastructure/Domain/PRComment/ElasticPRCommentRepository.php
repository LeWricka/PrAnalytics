<?php

namespace App\Infrastructure\Domain\PRComment;

use App\Domain\PRComment\PRComment;
use App\Domain\PRComment\PRCommentRepository;
use App\Infrastructure\Domain\ElasticClient;

class ElasticPRCommentRepository implements PRCommentRepository
{
    const COMMENTS_INDEX = 'comments';

    /**
     * @var ElasticClient
     */
    private $elasticClient;

    /**
     * @param ElasticClient $elasticClient
     */
    public function __construct(ElasticClient $elasticClient)
    {
        $this->elasticClient = $elasticClient;
    }

    /**
     * @param PRComment $prComment
     *
     * @return void
     */
    public function save(PRComment $prComment)
    {
        if (!$this->elasticClient->exists(self::COMMENTS_INDEX)) {
            $this->elasticClient->create(self::COMMENTS_INDEX);
        }

        $this->elasticClient->index(
            [
                'index' => self::COMMENTS_INDEX,
                'type' => 'comment',
                'body' => substr_replace(
                    $prComment->getContent(),
                    '"type":"'.$prComment->getPrCommentType()->getValue().'",',
                    1,
                    0
                )
            ]
        );
    }
}
