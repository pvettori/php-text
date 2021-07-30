# PHP Text: string manipulation for humans
[![Latest Version](https://img.shields.io/badge/version-0.0.1-orange)](https://github.com/pvettori/router/releases)
[![PHP Version](https://img.shields.io/badge/php-%E2%89%A57.0-blue)](https://www.php.net/)
[![MIT License](https://img.shields.io/badge/license-MIT-green)](https://github.com/pvettori/router/blob/master/LICENSE)

The functions in this library may not be as computationally efficient as the builtin PHP functions,
but they are quite useful if you want to avoid some headaches.

## Contents
- [String manipulation functions](#string-manipulation-functions)
  - [`Text::convert(string $string, string $encoding, [$from])`](#textconvertstring-string-string-encoding-from)
  - [`Text::format(string $format, array $values)`](#textformatstring-format-array-values)
  - [`Text::indexOf(string $substring, string $string, [int $offset], [bool $case_sensitive])`](#textindexofstring-substring-string-string-int-offset-bool-case_sensitive)
  - [`Text::join(array $array, [string $string])`](#textjoinarray-array-string-string)
  - [`Text::length(string $string, [string $encoding])`](#textlengthstring-string-string-encoding)
  - [`Text::lowercase(string $string)`](#textlowercasestring-string)
  - [`Text::match(string $string, string $pattern, [array &$groups])`](#textmatchstring-string-string-pattern-array-groups)
  - [`Text::matchAll(string $string, string $pattern, [array &$groups])`](#textmatchallstring-string-string-pattern-array-groups)
  - [`Text::pad(string $string, int $length, [string $chars], [int $side])`](#textpadstring-string-int-length-string-chars-int-side)
  - [`Text::split(string $string, string $pattern, [int $limit])`](#textsplitstring-string-string-pattern-int-limit)
  - [`Text::style(string $string, [int $style])`](#textstylestring-string-int-style)
  - [`Text::substring(string $string, [int $start], [int $length])`](#textsubstringstring-string-int-start-int-length)
  - [`Text::trim(string $string, [string $chars], [int $side])`](#texttrimstring-string-string-chars-int-side)
  - [`Text::uppercase(string $string)`](#textuppercasestring-string)
  - [`Text::wrap(string $string, int $length, [string $break], [int $cut])`](#textwrapstring-string-int-length-string-break-int-cut)
- [Locale settings functions](#locale-settings-functions)
  - [`Text::getLocale([string $key])`](#textgetlocalestring-key)
  - [`Text::getSystemLocale()`](#textgetsystemlocale)
  - [`Text::setLocale(array $locale)`](#textsetlocalearray-locale)


## String manipulation functions
### `Text::convert(string $string, string $encoding, [$from])`
Convert a string's character encoding.
|Parameter|Type|Description|
|---------|----|-----------|
|`$string`  |_string_          |The original string.|
|`$encoding`|_string_          |The target encoding.|
|`$from`    |_string\|string\[\]_|Optional.<br>The original encoding.<br>It is either an array, or a comma separated enumerated list.<br>If `$from` is not specified, then the internal encoding will be used.|
#### **Return** _string_<!-- omit in toc -->

### `Text::format(string $format, array $values)`
Format a string.
|Parameter|Type|Description|
|---------|----|-----------|
|`$format`|_string_|A conversion specification follows the following prototypes:<br>- `%{key}` or `${key}`<br>- `%{key:modifier}` or `${key:modifier}`<br>Keys in the format string must match the appropriate value key.<br>Nested values can be referenced via dot notation.|
|`$values`|_array_|The replacemente values.<br>The array can be either indexed or associative.|
#### Return _string_<!-- omit in toc -->

### `Text::indexOf(string $substring, string $string, [int $offset], [bool $case_sensitive])`
Find the position of a substring in a string.
|Parameter|Type|Description|
|---------|----|-----------|
|`$substring`     |_string_|The substring to search for.|
|`$string`        |_string_|The string to search in.|
|`$offset`        |_int_|Optional.<br>If positive, the search is performed left to right skipping the first **offset** bytes.<br>If negative, the search is performed right to left skipping the last **offset** bytes.<br>Default:`0`.|
|`$case_sensitive`|_bool_|Optional.<br>If `false`, serarch will be case-insensitive. Default: `true`.|
#### Return _int_<!-- omit in toc -->
A return value of `-1` means that the substing was not found.

### `Text::join(array $array, [string $string])`
Join array elements with a string.
|Parameter|Type|Description|
|---------|----|-----------|
|`$array`|_array_|The array of strings to join.|
|`$string`|_string_|Optional.<br>The string used to join the array items together.|
#### Return _string_<!-- omit in toc -->

### `Text::length(string $string, [string $encoding])`
Get a string length.
|Parameter|Type|Description|
|---------|----|-----------|
|`$string`  |_string_|The string being measured.|
|`$encoding`|_string_|Optional.<br>The character encoding.|
#### Return _int_<!-- omit in toc -->

### `Text::lowercase(string $string)`
Convert a string to lowercase.
|Parameter|Type|Description|
|---------|----|-----------|
|`$string`|_string_|The string to be converted to lowercase.|
#### Return _string_<!-- omit in toc -->

### `Text::match(string $string, string $pattern, [array &$groups])`
Perform a string match.
|Parameter|Type|Description|
|---------|----|-----------|
|`$string` |_string_|The input string.|
|`$pattern`|_string_|The characters or regex to search for.|
|`$groups` |_array_ |Optional.<br>Array of all captured groups.|
#### Return _string|null_<!-- omit in toc -->

### `Text::matchAll(string $string, string $pattern, [array &$groups])`
Perform a global string match.
|Parameter|Type|Description|
|---------|----|-----------|
|`$string` |_string_|The input string.|
|`$pattern`|_string_|The characters or regex to search for.|
|`$groups` |_array\|null_|Optional.<br>Array of all captured groups indexed by global match offset.|
#### Return _array_<!-- omit in toc -->
Returns an array of all the full matches indexed by offset.

### `Text::pad(string $string, int $length, [string $chars], [int $side])`
Pad a string to a certain length with another string.
|Parameter|Type|Description|
|---------|----|-----------|
|`$string`|_string_|The original string.|
|`$length`|_int_   |The string target length.|
|`$chars` |_string_|Optional.<br>The padding characters.<br>Default: `' '`|
|`$side`  |_int_   |Optional.<br>The padding side (`Text::PAD_BOTH`\|`Text::PAD_LEFT`\|`Text::PAD_RIGHT`).<br>Default: `Text::PAD_LEFT`.|
#### Return _string_<!-- omit in toc -->

### `Text::split(string $string, string $pattern, [int $limit])`
Split a string into chunks.
|Parameter|Type|Description|
|---------|----|-----------|
|`$string` |_string_|The original string.|
|`$pattern`|_string_|The characters or regex used as splitter.|
|`$limit`  |_int_   |Optional.<br>The maximum number of cuts (`0` - or negative numbers - means no limits).|
#### Return _array_<!-- omit in toc -->

### `Text::style(string $string, [int $style])`
Apply a style to a string.
|Parameter|Type|Description|
|---------|----|-----------|
|`$string`|_string_|The original string.|
|`$style` |_int_   |Optional.<br>The style to be applied.<br>Accepted values:<br>- `Text::STYLE_TEXT_PARAGRAPH`<br>- `Text::STYLE_TEXT_TITLE`<br>- `Text::STYLE_TEXT_LOWERCASE`<br>- `Text::STYLE_TEXT_UPPERCASE`<br>- `Text::STYLE_VAR_CAMEL_CASE`<br>- `Text::STYLE_VAR_KEBAB_CASE`<br>- `Text::STYLE_VAR_PASCAL_CASE`<br>- `Text::STYLE_VAR_SNAKE_CASE`<br>- `Text::STYLE_VAR_LOWER_CASE`<br>- `Text::STYLE_VAR_UPPER_CASE`<br>Default: `Text::STYLE_TEXT_PARAGRAPH`.|
#### Return _string_<!-- omit in toc -->

### `Text::substring(string $string, [int $start], [int $length])`
Return part of a string.
|Parameter|Type|Description|
|---------|----|-----------|
|`$string`|_string_|The original string.|
|`$start` |_int_   |Optional.<br>Start position.|
|`$length`|_int_   |Optional.<br>Length of the extracted string.|
#### Return _string_<!-- omit in toc -->

### `Text::trim(string $string, [string $chars], [int $side])`
Strip whitespace (or other characters) from the beginning and/or end of a string.
|Parameter|Type|Description|
|---------|----|-----------|
|`$string`|_string_|The original string.|
|`$chars` |_string_|Optional.<br>Characters to be trimmed.<br>Default: `" \t\n\r\0\x0B"`.|
|`$side`  |_int_   |Optional.<br>The trim side (`Text::TRIM_BOTH`\|`Text::TRIM_LEFT`\|`Text::TRIM_RIGHT`).<br>Default: `Text::TRIM_BOTH`.|
#### Return _string_<!-- omit in toc -->

### `Text::uppercase(string $string)`
Convert a string to uppercase.
|Parameter|Type|Description|
|---------|----|-----------|
|`$string`|_string_|The string to be converted to uppercase.|
#### Return _string_<!-- omit in toc -->

### `Text::wrap(string $string, int $length, [string $break], [int $cut])`
Wrap a string to a given number of characters.
|Parameter|Type|Description|
|---------|----|-----------|
|`$string`|_string_|The original string.|
|`$length`|_int_   |The maximum length of a line.|
|`$break` |_string_|Optional.<br>Line break character.<br>Default: `"\n"`.|
|`$cut`   |_int_   |Optional.<br>How to cut the line if a word exceeds maximum length.<br>Accepted values:<br>- `Text::WRAP_AFTER`<br>- `Text::WRAP_BEFORE`<br>- `Text::WRAP_BREAK`<br>Default: `Text::WRAP_BEFORE`.|
#### Return _string_<!-- omit in toc -->

## Locale settings functions
> **WARNING:** These functions apply to the staticText class only and DO NOT affect PHP locale settings.
### `Text::getLocale([string $key])`
Get locale settings. \
Initially equals to system locale settings.
|Parameter|Type|Description|
|---------|----|-----------|
|`$key`|_string_|Optional.<br>If `$key` is provided then only the corresponding value is returned.|
#### Return _mixed_<!-- omit in toc -->

### `Text::getSystemLocale()`
Get settings as per system locale.
#### Return _array_<!-- omit in toc -->

### `Text::setLocale(array $locale)`
Set locale Settings.
|Parameter|Type|Description|
|---------|----|-----------|
|`$locale`|_array_|Array of locale settings.<br>New settings are merged with current settings.|
