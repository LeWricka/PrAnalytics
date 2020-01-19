<?php

namespace App\Tests\Unit\Domain\PRComment;

use App\Domain\PRComment\PRComment;
use App\Domain\PRComment\PRCommentType;

class PRCommentTestDataBuilder
{
    /**
     * @var PRComment
     */
    private $prComment;

    /**
     * @param PRCommentType $prCommentType
     * @param string        $content
     */
    private function __construct(PRCommentType $prCommentType, string $content)
    {
        $this->prComment = new PRComment($prCommentType, $content);
    }

    /**
     * @param PRCommentType $prCommentType
     * @param string        $content
     *
     * @return PRCommentTestDataBuilder
     */
    public static function aPRComment(PRCommentType $prCommentType, string $content = 'content')
    {
        return new self($prCommentType, $content);
    }

    /**
     * @return PRComment
     */
    public function build(): PRComment
    {
        return $this->prComment;
    }
}
