<?php

namespace App\Domain\PRComment;

class PRComment
{
    /**
     * @var PRCommentType
     */
    private $prCommentType;

    /**
     * @var string
     */
    private $content;

    /**
     * @param PRCommentType $prCommentType
     * @param string        $content
     */
    public function __construct(PRCommentType $prCommentType, string $content)
    {
        $this->prCommentType = $prCommentType;
        $this->content = $content;
    }

    /**
     * @return PRCommentType
     */
    public function getPrCommentType(): PRCommentType
    {
        return $this->prCommentType;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }
}
