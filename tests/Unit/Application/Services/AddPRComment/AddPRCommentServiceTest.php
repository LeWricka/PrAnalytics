<?php

namespace App\Tests\Unit\Application\Services\AddPRComment;

use App\Application\Services\AddPRComment\AddPRCommentService;
use App\Domain\PRComment\PRCommentRepository;
use App\Domain\PRComment\PRCommentType;
use App\Tests\Unit\Domain\PRComment\PRCommentTestDataBuilder;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;

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
    public function saves_pr_testing_comment()
    {
        $commentData = 'commentData';
        $requestFormattedData = ['comment' => ['body' => '[testing]...'], 'repository' => ['full_name' => 'repo/name']];
        $prCommentType = new PRCommentType('testing');
        $expectedPRComment = PRCommentTestDataBuilder::aPRComment($prCommentType, $commentData)->build();
        $this->prCommentRepository->save($expectedPRComment, 'repo-name')->shouldBeCalledOnce();

        $this->addPRCommentService->execute($commentData, $requestFormattedData);
    }

    /**
     * @test
     */
    public function saves_pr_testing_comment_with_uppercase_repository()
    {
        $commentData = 'commentData';
        $requestFormattedData = ['comment' => ['body' => '[testing]...'], 'repository' => ['full_name' => 'repo/Name']];
        $prCommentType = new PRCommentType('testing');
        $expectedPRComment = PRCommentTestDataBuilder::aPRComment($prCommentType, $commentData)->build();
        $this->prCommentRepository->save($expectedPRComment, 'repo-name')->shouldBeCalledOnce();

        $this->addPRCommentService->execute($commentData, $requestFormattedData);
    }

    /**
     * @test
     * @expectedException Symfony\Component\HttpKernel\Exception\HttpException
     */
    public function error_if_there_is_no_comment_data()
    {
        $commentData = 'commentData';
        $requestFormattedData = ['comment' => ['other' => '[testing]...'], 'repository' => ['full_name' => 'repo/name']];
        $this->prCommentRepository->save(Argument::any(), Argument::any())->shouldNotBeCalled();

        $this->addPRCommentService->execute($commentData, $requestFormattedData);
    }

    /**
     * @test
     * @expectedException Symfony\Component\HttpKernel\Exception\HttpException
     */
    public function error_given_if_there_is_no_repository_data()
    {
        $commentData = 'commentData';
        $requestFormattedData = ['comment' => ['body' => '[testing]...'], 'repository' => ['name' => 'repo/name']];
        $this->prCommentRepository->save(Argument::any(), Argument::any())->shouldNotBeCalled();

        $this->addPRCommentService->execute($commentData, $requestFormattedData);
    }
}
