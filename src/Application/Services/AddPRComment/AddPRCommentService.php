<?php

namespace App\Application\Services\AddPRComment;

use App\Domain\PRComment\PRComment;
use App\Domain\PRComment\PRCommentRepository;
use App\Domain\PRComment\PRCommentType;

class AddPRCommentService
{
    /**
     * @var PRCommentRepository
     */
    private $prCommentRepository;

    /**
     * @param PRCommentRepository $prCommentRepository
     */
    public function __construct(PRCommentRepository $prCommentRepository)
    {
        $this->prCommentRepository = $prCommentRepository;
    }

    /**
     * @param string $commentData
     * @param array  $requestFormattedData
     */
    public function execute(string $commentData, array $requestFormattedData)
    {
        $prCommentType = PRCommentType::build($requestFormattedData['comment']['body']);
        $prComment = new PRComment($prCommentType, $commentData);
        $this->prCommentRepository->save($prComment);
    }
}
