<?php

namespace App\Domain\PRComment;

class PRCommentType
{
    public const TESTING = 'Testing';
    public const ARCHITECTURE = 'Architecture';
    public const NAMING = 'Naming';
    public const REFACTORING = 'Refactoring';
    public const OTHER = 'Other';

    /**
     * @var string
     */
    private $type;

    /**
     * @param string $type
     */
    private function __construct(string $type)
    {
        $this->type = $type;
    }

    /**
     * @return PRCommentType
     */
    public static function buildTesting(){
        return new self(self::TESTING);
    }

    /**
     * @return PRCommentType
     */
    public static function buildOther(){
        return new self(self::OTHER);
    }

    /**
     * @param string $type
     *
     * @return PRCommentType
     */
    public static function build(string $type): PRCommentType
    {
        $type = strtolower($type);
        if (strpos($type, '[tes') !== false) {
            return new self(self::TESTING);
        } elseif (strpos($type, '[arch') !== false) {
            return new self(self::ARCHITECTURE);
        } elseif (strpos($type, '[nam') !== false) {
            return new self(self::NAMING);
        } elseif (strpos($type, '[ref') !== false) {
            return new self(self::REFACTORING);
        }
        return new self(self::OTHER);
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->type;
    }
}
