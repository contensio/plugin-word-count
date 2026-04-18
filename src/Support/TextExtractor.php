<?php

/**
 * Word Count — Contensio plugin.
 * https://contensio.com
 *
 * @copyright   Copyright (c) 2026 Iosif Gabriel Chimilevschi
 * @license     https://www.gnu.org/licenses/agpl-3.0.txt  AGPL-3.0-or-later
 */

namespace Contensio\Plugins\WordCount\Support;

use Contensio\Models\Content;
use Contensio\Models\ContentTranslation;

/**
 * Extracts a plain-text string from a Content model's title, excerpt,
 * and block tree so that word counts can be calculated.
 */
class TextExtractor
{
    /**
     * Return a plain-text concatenation of all readable content fields.
     */
    public static function fromContent(Content $content, ContentTranslation $translation): string
    {
        $parts = [];

        if ($translation->title) {
            $parts[] = $translation->title;
        }

        if ($translation->excerpt) {
            $parts[] = strip_tags($translation->excerpt);
        }

        $blocks = $content->blocks;
        if (! empty($blocks)) {
            $parts[] = static::flattenBlocks(is_string($blocks) ? json_decode($blocks, true) : $blocks);
        }

        return implode(' ', array_filter($parts));
    }

    /**
     * Recursively walk a block tree and collect all string leaf values,
     * stripping any embedded HTML tags.
     */
    private static function flattenBlocks(mixed $data): string
    {
        if (is_string($data)) {
            return strip_tags($data);
        }

        if (! is_array($data)) {
            return '';
        }

        $parts = [];
        foreach ($data as $value) {
            $text = static::flattenBlocks($value);
            if ($text !== '') {
                $parts[] = $text;
            }
        }

        return implode(' ', $parts);
    }
}
