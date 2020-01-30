<?php

namespace App\Tests\Unit\Domain\PRComment;

use App\Domain\PRComment\PRCommentType;
use PHPUnit\Framework\TestCase;

class PRCommentTypeTest extends TestCase
{
    /**
     * @test
     */
    public function adds_testing_type_comment()
    {
        $commentType = PRCommentType::buildFromData('[testing]');

        $this->assertEquals('testing', $commentType->getValue());
    }

    /**
     * @test
     */
    public function adds_testing_type_comment_having_capital_letters()
    {
        $commentType = PRCommentType::buildFromData('[Testing]');

        $this->assertEquals('testing', $commentType->getValue());
    }

    /**
     * @test
     */
    public function adds_other_type_comment()
    {
        $commentType = PRCommentType::buildFromData('[tis');

        $this->assertEquals(PRCommentType::GENERIC, $commentType->getValue());
    }
}
