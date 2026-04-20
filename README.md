# Word Count

Displays the word count of a post in the post meta row. The count includes the title, excerpt, and all block content - the same corpus that the Reading Time plugin uses for its estimate.

**Features:**
- Word count shown inline in the post meta row
- Counts words from the title, excerpt, and all block content
- Renders after reading time (priority 5) at priority 6
- No admin configuration, no database queries at render time

---

## Requirements

- Contensio 2.0 or later

---

## Installation

### Composer

```bash
composer require contensio/plugin-word-count
```

### Manual

Copy the plugin directory and register the service provider via the admin plugin manager.

No migrations or configuration required.

---

## How it works

The plugin hooks into `contensio/frontend/post-meta` at priority 6. It uses `TextExtractor::fromContent()` to walk the post's block tree and concatenate all string values into a single plain-text corpus. PHP's `str_word_count()` then counts the words in that string.

### TextExtractor

`Contensio\Plugins\WordCount\Support\TextExtractor` is a self-contained helper class that recursively flattens the block JSON structure into plain text:

```
title + excerpt + flatten(blocks[*].data[*].translations[*])
```

All HTML tags are stripped before counting via `strip_tags()`.

---

## Output

The word count appears in the post meta row as:

```
· 1,234 words
```

Numbers are formatted with `number_format()` for readability (thousands separator).

---

## Hook reference

| Hook | Priority | Description |
|------|----------|-------------|
| `contensio/frontend/post-meta` | 6 | Injects the word count into the post meta row |
