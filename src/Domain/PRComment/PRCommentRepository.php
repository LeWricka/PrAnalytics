<?php

namespace App\Domain\PRComment;

interface PRCommentRepository
{
    /**
     * @param PRComment $prComment
     *
     * @return void
     */
    public function save(PRComment $prComment);
}
