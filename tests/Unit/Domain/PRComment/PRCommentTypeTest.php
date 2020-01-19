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
        $commentType = PRCommentType::build('[tes');

        $this->assertEquals(PRCommentType::TESTING, $commentType->getValue());
    }

    /**
     * @test
     */
    public function adds_testing_type_comment_having_capital_letters()
    {
        $commentType = PRCommentType::build('[Tes');

        $this->assertEquals(PRCommentType::TESTING, $commentType->getValue());
    }


    /**
     * @test
     */
    public function adds_other_type_comment()
    {
        $commentType = PRCommentType::build('[tis');

        $this->assertEquals(PRCommentType::OTHER, $commentType->getValue());
    }
}
