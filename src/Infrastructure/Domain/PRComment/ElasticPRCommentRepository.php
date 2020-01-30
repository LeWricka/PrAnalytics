<?php

namespace App\Infrastructure\Domain\PRComment;

use App\Domain\PRComment\PRComment;
use App\Domain\PRComment\PRCommentRepository;
use App\Infrastructure\Domain\ElasticClient;

class ElasticPRCommentRepository implements PRCommentRepository
{
    const COMMENTS_INDEX = 'comments';
    const COMMENT_TYPE = 'comment';

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
     * @param string    $originRepository
     */
    public function save(PRComment $prComment, string $originRepository)
    {
        if (!$this->elasticClient->exists($originRepository.'-'.self::COMMENTS_INDEX)) {
            $this->elasticClient->create($originRepository.'-'.self::COMMENTS_INDEX);
        }

        $this->elasticClient->index(
            [
                'index' => $originRepository.'-'.self::COMMENTS_INDEX,
                'type' => self::COMMENT_TYPE,
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
