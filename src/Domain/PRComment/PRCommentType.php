<?php

namespace App\Domain\PRComment;

class PRCommentType
{
    public const GENERIC = 'generic';

    /**
     * @var string
     */
    private $type;

    /**
     * @param string $type
     */
    public function __construct(string $type)
    {
        $this->type = $type;
    }

    /**
     * @return PRCommentType
     */
    public static function buildGeneric(){
        return new self(self::GENERIC);
    }

    /**
     * @param string $type
     *
     * @return PRCommentType
     */
    public static function buildFromData(string $type): PRCommentType
    {
        $type = strtolower($type);
        if (preg_match('/\[(.*?)]/', $type, $match) == 1) {
            return new self($match[1]);
        }

        return new self(self::GENERIC);
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->type;
    }
}
