<?php

namespace App\Domain\PRComment;

interface PRCommentRepository
{
    /**
     * @param PRComment $prComment
     * @param string    $originRepository
     *
     * @return void
     */
    public function save(PRComment $prComment, string $originRepository);
}
