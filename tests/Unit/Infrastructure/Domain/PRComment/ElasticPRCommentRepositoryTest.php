<?php

namespace App\Tests\Unit\Infrastructure\Domain\PRComment;

use App\Domain\PRComment\PRCommentType;
use App\Infrastructure\Domain\ElasticClient;
use App\Infrastructure\Domain\PRComment\ElasticPRCommentRepository;
use App\Tests\Unit\Domain\PRComment\PRCommentTestDataBuilder;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;

class ElasticPRCommentRepositoryTest extends TestCase
{
    /**
     * @var ElasticPRCommentRepository
     */
    private $elasticPRCommentRepository;

    /**
     * @var ElasticClient
     */
    private $elasticClient;

    /**
     * @setUp
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->elasticClient = $this->prophesize(ElasticClient::class);
        $this->elasticPRCommentRepository = new ElasticPRCommentRepository($this->elasticClient->reveal());
    }

    /**
     * @test
     */
    public function saves_pr_comment()
    {
        $prComment = PRCommentTestDataBuilder::aPRComment(PRCommentType::buildGeneric(), '{"content"}')->build();
        $expectedIndexParameters = [
            'index' => 'repo-name-'.ElasticPRCommentRepository::COMMENTS_INDEX,
            'type' => ElasticPRCommentRepository::COMMENT_TYPE,
            'body' => '{"type":"generic","content"}'
        ];

        $this->elasticClient->exists('repo-name-'.ElasticPRCommentRepository::COMMENTS_INDEX)->willReturn(true);
        $this->elasticClient->create(Argument::any())->shouldNotBeCalled();
        $this->elasticClient->index($expectedIndexParameters)->willReturn([]);

        $this->elasticPRCommentRepository->save($prComment, 'repo-name');
    }

    /**
     * @test
     */
    public function creates_index_and_saves_pr_comment()
    {
        $prComment = PRCommentTestDataBuilder::aPRComment(PRCommentType::buildGeneric(), '{"content"}')->build();
        $expectedIndexParameters = [
            'index' => 'repo-name-'.ElasticPRCommentRepository::COMMENTS_INDEX,
            'type' => ElasticPRCommentRepository::COMMENT_TYPE,
            'body' => '{"type":"generic","content"}'
        ];

        $this->elasticClient->exists('repo-name-'.ElasticPRCommentRepository::COMMENTS_INDEX)->willReturn(false);
        $this->elasticClient->create('repo-name-'.ElasticPRCommentRepository::COMMENTS_INDEX)->shouldBeCalled();
        $this->elasticClient->index($expectedIndexParameters)->willReturn([]);

        $this->elasticPRCommentRepository->save($prComment, 'repo-name');
    }
}
