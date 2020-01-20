<?php

namespace App\Application\Services\AddPRComment;

use App\Domain\PRComment\PRComment;
use App\Domain\PRComment\PRCommentRepository;
use App\Domain\PRComment\PRCommentType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

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
        if (!isset($requestFormattedData['comment']['body']) || !isset($requestFormattedData['repository']['full_name'])) {
            throw new HttpException(Response::HTTP_BAD_REQUEST, 'Invalid given data');
        }
        $prCommentType = PRCommentType::buildFromData($requestFormattedData['comment']['body']);
        $originRepository = strtolower(str_replace('/', '-', $requestFormattedData['repository']['full_name']));
        $prComment = new PRComment($prCommentType, $commentData);

        $this->prCommentRepository->save($prComment, $originRepository);
    }
}
