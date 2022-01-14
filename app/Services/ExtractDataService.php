<?php

namespace App\Services;

use App\Services\Interfaces\ExtractDataServiceInterface;

class ExtractDataService implements ExtractDataServiceInterface
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
    public function floatFromText(string $text, array $delimiters, array $replacements): float
    {
        foreach ($delimiters as $delimiter) {
            $data = explode($delimiter, $text);
            $text = $data[0];
        }

        return (float) str_replace($replacements[0], $replacements[1], $text);
    }
}
