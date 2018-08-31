<?php

namespace App\Service;

class ReadingTime
{
    protected $calculator;

    public function __construct(Calculator $calculator)
    {
        $this->calculator = $calculator;
    }

    const WORDS_PER_MINUTE = 250;

    public function computeReadingTime(string $content)
    {
        $readingTime = ceil(str_word_count($content) / self::WORDS_PER_MINUTE);

        return $this->calculator->addition($readingTime / 2, $readingTime / 2);
    }
}
