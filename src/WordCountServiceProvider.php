<?php

/**
 * Word Count — Contensio plugin.
 * https://contensio.com
 *
 * @copyright   Copyright (c) 2026 Iosif Gabriel Chimilevschi
 * @license     https://www.gnu.org/licenses/agpl-3.0.txt  AGPL-3.0-or-later
 */

namespace Contensio\Plugins\WordCount;

use Contensio\Models\Content;
use Contensio\Models\ContentTranslation;
use Contensio\Plugins\WordCount\Support\TextExtractor;
use Contensio\Support\Hook;
use Illuminate\Support\ServiceProvider;

class WordCountServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Inject word count into the post meta row at priority 6
        // (after reading time at priority 5, before print button at priority 20)
        Hook::add('contensio/frontend/post-meta', function (Content $content, ContentTranslation $translation): string {
            $text  = TextExtractor::fromContent($content, $translation);
            $count = str_word_count(strip_tags($text));

            if ($count === 0) {
                return '';
            }

            $label = number_format($count) . ' ' . ($count === 1 ? 'word' : 'words');

            return '<span>&middot;</span><span>' . e($label) . '</span>';
        }, 6);
    }
}
