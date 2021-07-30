<?php
/**
 * Class Text.
 * A static class that provides string manipulation functions.
 */
final class Text
{
    const PAD_BOTH = STR_PAD_BOTH;
    const PAD_LEFT = STR_PAD_LEFT;
    const PAD_RIGHT = STR_PAD_RIGHT;
    const TRIM_BOTH = 2;
    const TRIM_LEFT = 0;
    const TRIM_RIGHT = 1;
    const WRAP_AFTER = 0;
    const WRAP_BEFORE = 1;
    const WRAP_BREAK = 2;
    const STYLE_TEXT_PARAGRAPH = 1;
    const STYLE_TEXT_TITLE = 2;
    const STYLE_TEXT_LOWERCASE = 3;
    const STYLE_TEXT_UPPERCASE = 4;
    const STYLE_VAR_CAMEL_CASE = 10;
    const STYLE_VAR_KEBAB_CASE = 11;
    const STYLE_VAR_PASCAL_CASE = 12;
    const STYLE_VAR_SNAKE_CASE = 13;
    const STYLE_VAR_LOWER_CASE = 13; // alias of Text::STYLE_VAR_SNAKE_CASE
    const STYLE_VAR_UPPER_CASE = 14;

    private static $locale = [];

    public function __construct()
    {
        throw new \BadMethodCallException('Final class Text cannot be instantiated');
    }

    /**
     * Convert a string's character encoding.
     *
     * @see [Supported Encodings](https://www.php.net/manual/en/mbstring.supported-encodings.php)
     *
     * @param string          $string   The original string.
     * @param string          $encoding The target encoding.
     * @param string|string[] $from     [optional] The original encoding.
     *
     * @return string
     */
    public static function convert(string $string, string $encoding, $from = null)
    {
        if (!function_exists('mb_convert_encoding')) {
            throw new \RuntimeException('This function requires the \'mbstring\' extension; please enable it in your php.ini file');
        }

        return mb_convert_encoding($string, $encoding, $from);
    }

    /**
     * Format a string.
     *
     * @param string $format The output format string.
     *                       A conversion specification follows the following prototypes:
     *                       - `%{key}` | `${key}`
     *                       - `%{key:modifier}` | `${key:modifier}`
     *                       Keys in the format string must match the appropriate value key.
     *                       Nested values can be referenced via dot notation.
     * @param array  $values The replacemente values.
     *                       The array can be either indexed or associative.
     *
     * @return string
     */
    public static function format(string $format, array $values)
    {
        static $regex = '/(?:[\%\$])\{(?<key>\d+|((\[\d+\]|[a-zA-Z_]\w*)(\.(\[\d+\]|[a-zA-Z_]\w*))*))(:(?<mod>[bcdfhilu]|.*))?\}/';

        if (preg_match_all($regex, $format, $matches, PREG_PATTERN_ORDER)) {
            $values = static::arrayValues($values);
            foreach (array_unique($matches[0]) as $index => $pattern) {
                $val = $values[$matches['key'][$index]] ?? '';
                if ($mod = $matches['mod'][$index] ?? null) {
                    switch ($mod) {
                        case 'b': $val = $val ? 'true' : 'false'; break;
                        case 'c': $val = static::numberFormat(floatval($val), '$ 0,000.'.str_repeat('0', static::getLocale('currency_digits'))); break;
                        case 'd':
                        case 'f': $val = floatval($val); break;
                        case 'h': $val = dechex(intval($val)); break;
                        case 'i': $val = intval($val); break;
                        case 'l': $val = strtolower($val); break;
                        case 'u': $val = strtoupper($val); break;
                        default: $val = static::numberFormat($val, $mod);
                    }
                }
                $format = str_replace($pattern, $val, $format);
            }
        }

        return $format;
    }

    /**
     * Find the position of a substring in a string.
     *
     * A return value of `-1` means that the substing was not found.
     *
     * @param string $substring      The substring to search for.
     * @param string $string         The string to search in.
     * @param int    $offset         [optional]
     *                               If positive, the search is performed left to right skipping the first **offset** bytes.
     *                               If negative, the search is performed right to left skipping the last **offset** bytes.
     * @param bool   $case_sensitive [optional]
     *                               If `false`, serarch will be case-insensitive. Default: `true`.
     *
     * @return int
     */
    public static function indexOf(string $substring, string $string, int $offset = 0, bool $case_sensitive = true)
    {
        $fn = 'strpos';
        if (!$case_sensitive) {
            $string = strtolower($string);
            $substring = strtolower($substring);
        }
        if ($offset < 0) {
            $offset = -$offset;
            $fn = 'strrpos';
        }
        $index = $fn($string, $substring, $offset);
        if ($index === false) {
            $index = -1;
        }

        return $index;
    }

    /**
     * Join array elements with a string.
     *
     * @param string|string[] $array  The array of strings to join.
     * @param string          $string [optional]
     *
     * @return string
     */
    public static function join(array $array, string $string = '')
    {
        return implode($string, $array);
    }

    /**
     * Get a string length.
     *
     * @param string $string   The string being measured.
     * @param string $encoding [optional] The character encoding.
     *
     * @return int
     */
    public static function length(string $string, string $encoding = null)
    {
        if (in_array($length = @mb_strlen($string, $encoding), [false, null], true)) {
            $length = strlen($string);
        }

        return $length;
    }

    /**
     * Convert a string to lowercase.
     *
     * @param string $string
     *
     * @return string
     */
    public static function lowercase(string $string)
    {
        return @mb_convert_case($string, MB_CASE_LOWER) ?: strtolower($string);
    }

    /**
     * Perform a regular expression match.
     *
     * @param string $string  The input string.
     * @param string $pattern The pattern to search for, as a string.
     *
     * @return array
     */
    public static function match(string $string, string $pattern)
    {
        preg_match($pattern, $string, $matches);

        return $matches;
    }

    /**
     * Perform a global regular expression match.
     *
     * @param string $string  The input string.
     * @param string $pattern The pattern to search for, as a string.
     *
     * @return array
     */
    public static function matchAll(string $string, string $pattern)
    {
        preg_match_all($pattern, $string, $matches, PREG_SET_ORDER);

        return $matches;
    }

    /**
     * Pad a string to a certain length with another string.
     *
     * @param string $string The original string.
     * @param int    $length The string target length;
     * @param string $chars  [optional] The padding characters. Default: `' '`.
     * @param int    $side   [optional] The padding side (`Text::PAD_BOTH|Text::PAD_LEFT|Text::PAD_RIGHT`).
     *                       Default: `Text::PAD_LEFT`.
     *
     * @return string
     */
    public static function pad(string $string, int $length, string $chars = ' ', int $side = self::PAD_LEFT)
    {
        return str_pad($string, max(0, $length), $chars, $side);
    }

    /**
     * Split a string into chunks.
     *
     * @param string $string  The original string.
     * @param string $pattern The characters or regex used as splitter.
     * @param int    $limit   [optional] The maximum number of cuts (`0` - or negative numbers - means no limits).
     *                        Default: `0`.
     *
     * @return array
     */
    public static function split(string $string, string $pattern, int $limit = 0)
    {
        if (in_array(@preg_match($pattern, ''), [false, null], true)) {
            return ($limit > 0)
                ? explode($pattern, $string, $limit)
                : explode($pattern, $string);
        }

        return preg_split($pattern, $string, max(0, $limit));
    }

    /**
     * Apply a style to a given string.
     *
     * @param string $string The original string.
     * @param int    $style  [optional] The style to be applied. Default: `Text::STYLE_TEXT_PARAGRAPH`.
     *                       Accepted values:
     *                       - `Text::STYLE_TEXT_PARAGRAPH`
     *                       - `Text::STYLE_TEXT_TITLE`
     *                       - `Text::STYLE_TEXT_LOWERCASE`
     *                       - `Text::STYLE_TEXT_UPPERCASE`
     *                       - `Text::STYLE_VAR_CAMEL_CASE`
     *                       - `Text::STYLE_VAR_KEBAB_CASE`
     *                       - `Text::STYLE_VAR_PASCAL_CASE`
     *                       - `Text::STYLE_VAR_SNAKE_CASE`
     *                       - `Text::STYLE_VAR_LOWER_CASE`
     *                       - `Text::STYLE_VAR_UPPER_CASE`
     *
     * @return string
     */
    public static function style(string $string, int $style = self::STYLE_TEXT_PARAGRAPH)
    {
        static $parseWords;
        if (!$parseWords) {
            $parseWords = function (string $string): array {
                $string = preg_replace('/(?<!^)([A-Z][a-z0-9]+|\d+)/', ' $1', $string);
                $string = preg_replace('/[^a-z0-9_]/', ' ', strtolower($string));

                return explode(' ', $string);
            };
        }

        switch ($style) {
            case static::STYLE_TEXT_PARAGRAPH:
                $string = preg_replace('/\s*([:;,])\s*/s', '$1 ', $string);
                $phrases = preg_split('/\s*\.\s*/', $string);

                return implode('. ', array_map(function ($phrase) {
                    return strtoupper(substr($phrase, 0, 1)).substr($phrase, 1);
                }, $phrases));
            case static::STYLE_TEXT_TITLE:
                if (!function_exists('mb_convert_case')) {
                    throw new \RuntimeException('This function requires the \'mbstring\' extension; please enable it in your php.ini file');
                }
                $chunks = preg_split('/\b([A-Z]{2,})\b/', $string, 0, PREG_SPLIT_DELIM_CAPTURE);
                $chunks = array_map(function ($chunk) {
                    return ($chunk === strtoupper($chunk)) ? $chunk : mb_convert_case($chunk, MB_CASE_TITLE);
                }, $chunks);

                return implode('', $chunks);
            case static::STYLE_TEXT_LOWERCASE:
                return @mb_convert_case($string, MB_CASE_LOWER) ?: strtolower($string);
            case static::STYLE_TEXT_UPPERCASE:
                return @mb_convert_case($string, MB_CASE_UPPER) ?: strtoupper($string);
            case static::STYLE_VAR_CAMEL_CASE:
                $words = $parseWords($string);

                return array_shift($words).implode('', array_map('ucfirst', $words));
            case static::STYLE_VAR_KEBAB_CASE:
                return implode('-', $parseWords($string));
            case static::STYLE_VAR_PASCAL_CASE:
                return implode('', array_map('ucfirst', $parseWords($string)));
            case static::STYLE_VAR_SNAKE_CASE:
                return implode('_', $parseWords($string));
            case static::STYLE_VAR_UPPER_CASE:
                return strtoupper(implode('_', $parseWords($string)));
        }

        return $string;
    }

    /**
     * Return part of a string.
     *
     * @param string $string The original string.
     * @param int    $start  [optional]
     * @param int    $length [optional]
     *
     * @return string
     */
    public static function substring(string $string, int $start = null, int $length = null)
    {
        return substr($string, $start, $length);
    }

    /**
     * Strip whitespace (or other characters) from the beginning and/or end of a string.
     *
     * @param string $string The original string.
     * @param string $chars  [optional] Characters to be trimmed.
     * @param int    $side   [optional] The trim side (`Text::TRIM_BOTH|Text::TRIM_LEFT|Text::TRIM_RIGHT`).
     *                       Default: `Text::TRIM_BOTH`.
     *
     * @return string
     */
    public static function trim(string $string, string $chars = " \t\n\r\0\x0B", int $side = self::TRIM_BOTH)
    {
        if ($side === static::TRIM_LEFT) {
            return ltrim($string, $chars);
        } elseif ($side === static::TRIM_RIGHT) {
            return rtrim($string, $chars);
        } elseif ($side === static::TRIM_BOTH) {
            return trim($string, $chars);
        }

        return $string;
    }

    /**
     * Convert a string to uppercase.
     *
     * @param string $string
     *
     * @return string
     */
    public static function uppercase(string $string)
    {
        return @mb_convert_case($string, MB_CASE_UPPER) ?: strtoupper($string);
    }

    /**
     * Wrap a string to a given number of characters.
     *
     * @param string $string The original string.
     * @param int    $length The maximum length of a line.
     * @param string $break  [optional] Line break character.
     * @param bool   $cut    [optional] How to cut the line if a word exceeds maximum length.
     *
     * @return string
     */
    public static function wrap(string $string, int $length, string $break = "\n", int $cut = self::WRAP_BEFORE)
    {
        switch ($cut) {
            case static::WRAP_AFTER:
                $regex = "/(.{{$length},})\s+/";
                break;
            case static::WRAP_BEFORE:
                $regex = sprintf('/(.{0,%s}[^\s]|[^\s]+)\s\h*/', $length - 1);
                break;
            default:
                $regex = "/(.{0,{$length}})\s*/";
        }

        $lines = preg_split($regex, preg_replace('/\R/', "\n", trim($string)), -1, PREG_SPLIT_DELIM_CAPTURE);

        return implode($break, array_map('trim', array_filter($lines)));
    }

    /* LOCALE */

    public static function getLocale(string $key = null)
    {
        if (!static::$locale) {
            static::$locale = static::getSystemLocale();
        }

        return $key ? (static::$locale[$key] ?? null) : static::$locale;
    }

    public static function getSystemLocale(): array
    {
        $locale = localeconv();

        if ($locale['int_frac_digits'] > 9) {
            $locale['int_frac_digits'] = 2;
        }

        return [
            'currency_abbrev' => $locale['int_curr_symbol'] ?: 'USD',
            'currency_digits' => $locale['int_frac_digits'] ?: 2,
            'currency_symbol' => $locale['currency_symbol'] ?: '$',
            'decimal_separator' => $locale['decimal_point'] ?: '.',
            'thousand_separator' => $locale['thousands_sep'] ?: ',',
            'timezone' => date_default_timezone_get(),
        ];
    }

    public static function setLocale(array $locale)
    {
        static::$locale = array_merge(static::getLocale(), $locale);
    }

    /* PRIVATE METHODS */

    private static function arrayValues(array $array, string $prefix = null, array &$result = [])
    {
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $value = static::arrayValues($value, is_int($key) ? "[{$key}]." : "{$key}.", $result);
            } else {
                $key = (is_int($key) && !is_null($prefix)) ? "[{$key}]" : $key;
                $result[$prefix.$key] = $value;
            }
        }

        return $result;
    }

    private static function numberFormat($value, string $format = null): string
    {
        $decimalSeparator = static::getLocale('decimal_separator');
        $thousandSeparator = static::getLocale('thousand_separator');
        $format = str_replace('\$', '$', preg_replace('/(?<!\\\\)\$/', static::getLocale('currency_symbol'), $format));

        if (is_null($format)) {
            /* SKIP */
        } elseif (preg_match('/^(?<ini>.*?)(?<int>0,000|0)(\.(?<dec>0+))?(?<end>.*?)$/', $format, $matches)) {
            $int = intval($value);
            $dec = abs(fmod($value, 1));
            if ('0,000' === $matches['int']) {
                $value = strrev(implode($thousandSeparator, str_split(strrev($int), 3)));
            }
            if ($len = strlen($matches['dec'] ?? '')) {
                $value .= $decimalSeparator.str_pad(substr((string) $dec, 2, $len), $len, '0');
            }
            $value = $matches['ini'].$value.$matches['end'];
        } elseif (@date($format)) {
            $timezone = timezone_open(static::getLocale('timezone'));
            if (is_string($value) && ($datetime = @date_create($value, $timezone))) {
                $value = date_format($datetime, $format);
            } elseif ($datetime = @date_create_from_format('U.u', (float) $value, $timezone)) {
                $value = date_format($datetime, $format);
            }
        }

        return "$value";
    }
}
