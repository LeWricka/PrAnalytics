<?php

namespace App\Tests\Unit\Application\Services\AddPRComment;

use App\Application\Services\AddPRComment\AddPRCommentService;
use App\Domain\PRComment\PRComment;
use App\Domain\PRComment\PRCommentRepository;
use App\Domain\PRComment\PRCommentType;
use App\Tests\Unit\Domain\PRComment\PRCommentTestDataBuilder;
use PHPUnit\Framework\TestCase;

class AddPRCommentServiceTest extends TestCase
{
    /**
     * @var PRCommentRepository
     */
    private $prCommentRepository;

    /**
     * @var AddPRCommentService
     */
    private $addPRCommentService;

    /**
     * @setUp
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->prCommentRepository = $this->prophesize(PRCommentRepository::class);
        $this->addPRCommentService = new AddPRCommentService($this->prCommentRepository->reveal());
    }

    /**
     * @test
     */
    public function savesPRTestingComment()
    {
        $commentData = 'commentData';
        $requestFormattedData = ['comment' => ['body' => '[testing]...']];

        $expectedPRComment = PRCommentTestDataBuilder::aPRComment(PRCommentType::buildTesting(), $commentData)->build();
        $this->prCommentRepository->save($expectedPRComment)->shouldBeCalledOnce();

        $this->addPRCommentService->execute($commentData, $requestFormattedData);
    }

}
