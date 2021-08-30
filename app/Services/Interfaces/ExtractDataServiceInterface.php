<?php

namespace App\Services\Interfaces;

interface ExtractDataServiceInterface
{
    /**
     * Extract а float from text
     * $delimiters should contains the separators on which the text will be split
     * $replacelents should contains the first and second arguments for str_replace function
     *
     * @param string $text
     * @param array $delimiters
     * @param array $replacements
     * @return float
     */
    public function extractFloatFromText(string $text, array $delimiters, array $replacements): float;
}
