<?php

namespace FixUTF8;

class Encoding
{
    /** @var array|string[] */
    protected static array $win1252ToUtf8 = [
        128 => "\xe2\x82\xac", // Euro Sign
        // UNASSIGNED
        130 => "\xe2\x80\x9a", // Single Low-9 Quotation Mark
        131 => "\xc6\x92",     // Latin Small Letter F With Hook
        132 => "\xe2\x80\x9e", // Double Low-9 Quotation Mark
        133 => "\xe2\x80\xa6", // Horizontal Ellipsis
        134 => "\xe2\x80\xa0", // Dagger
        135 => "\xe2\x80\xa1", // Double Dagger
        136 => "\xcb\x86",     // Modifier Letter Circumflex Accent
        137 => "\xe2\x80\xb0", // Per Mille Sign
        138 => "\xc5\xa0",     // Latin Capital Letter S With Caron
        139 => "\xe2\x80\xb9", // Single Left-Pointing Angle Quotation Mark
        140 => "\xc5\x92",     // Latin Capital Ligature OE
        // UNASSIGNED
        142 => "\xc5\xbd",     // Latin Capital Letter Z With Caron
        // UNASSIGNED
        145 => "\xe2\x80\x98", // Left Single Quotation Mark
        146 => "\xe2\x80\x99", // Right Single Quotation Mark
        147 => "\xe2\x80\x9c", // Left Double Quotation Mark
        148 => "\xe2\x80\x9d", // Right Double Quotation Mark
        149 => "\xe2\x80\xa2", // Bullet
        150 => "\xe2\x80\x93", // En Dash
        151 => "\xe2\x80\x94", // Em Dash
        152 => "\xcb\x9c",     // Small Tilde
        153 => "\xe2\x84\xa2", // Trade Mark Sign
        154 => "\xc5\xa1",     // Latin Small Letter S With Caron
        155 => "\xe2\x80\xba", // Single Right-Pointing Angle Quotation Mark
        156 => "\xc5\x93",     // Latin Small Ligature OE
        // UNASSIGNED
        158 => "\xc5\xbe",     // Latin Small Letter Z With Caron
        159 => "\xc5\xb8"      // Latin Capital Letter Y With Diaeresis
    ];

    /** @var array|string[] */
    protected static array $brokenUtf8ToUtf8 = [
        "\xe2\x82\xac" => "\xc2\x80", // Euro Sign
        // UNASSIGNED
        "\xe2\x80\x9a" => "\xc2\x82", // Single Low-9 Quotation Mark
        "\xc6\x92"     => "\xc2\x83", // Latin Small Letter F With Hook
        "\xe2\x80\x9e" => "\xc2\x84", // Double Low-9 Quotation Mark
        "\xe2\x80\xa6" => "\xc2\x85", // Horizontal Ellipsis
        "\xe2\x80\xa0" => "\xc2\x86", // Dagger
        "\xe2\x80\xa1" => "\xc2\x87", // Double Dagger
        "\xcb\x86"     => "\xc2\x88", // Modifier Letter Circumflex Accent
        "\xe2\x80\xb0" => "\xc2\x89", // Per Mille Sign
        "\xc5\xa0"     => "\xc2\x8a", // Latin Capital Letter S With Caron
        "\xe2\x80\xb9" => "\xc2\x8b", // Single Left-Pointing Angle Quotation Mark
        "\xc5\x92"     => "\xc2\x8c", // Latin Capital Ligature OE
        // UNASSIGNED
        "\xc5\xbd"     => "\xc2\x8e", // Latin Capital Letter Z With Caron
        // UNASSIGNED
        "\xe2\x80\x98" => "\xc2\x91", // Left Single Quotation Mark
        "\xe2\x80\x99" => "\xc2\x92", // Right Single Quotation Mark
        "\xe2\x80\x9c" => "\xc2\x93", // Left Double Quotation Mark
        "\xe2\x80\x9d" => "\xc2\x94", // Right Double Quotation Mark
        "\xe2\x80\xa2" => "\xc2\x95", // Bullet
        "\xe2\x80\x93" => "\xc2\x96", // En Dash
        "\xe2\x80\x94" => "\xc2\x97", // Em Dash
        "\xcb\x9c"     => "\xc2\x98", // Small Tilde
        "\xe2\x84\xa2" => "\xc2\x99", // Trade Mark Sign
        "\xc5\xa1"     => "\xc2\x9a", // Latin Small Letter S With Caron
        "\xe2\x80\xba" => "\xc2\x9b", // Single Right-Pointing Angle Quotation Mark
        "\xc5\x93"     => "\xc2\x9c", // Latin Small Ligature OE
        // UNASSIGNED
        "\xc5\xbe"     => "\xc2\x9e", // Latin Small Letter Z With Caron
        "\xc5\xb8"     => "\xc2\x9f"  // Latin Capital Letter Y With Diaeresis
    ];

    /**
     * Fix a garbled UTF-8 string that was encoded multiple times
     *
     * @param string $text
     * @return string
     */
    public static function fixUtf8(string $text): string
    {
        $last = "";
        while ($last <> $text) {
            $last = $text;
            $text = self::toUTF8(self::utf8Decode($text));
        }

        return self::toUTF8(self::utf8Decode($text));
    }

    /**
     * Converting almost all non-UTF8 to UTF8, while leaving UTF-8 characters alone.
     * It assumes that the encoding of the original string is either Windows-1252 or ISO 8859-1.
     *
     * @param string $text
     * @return string
     */
    protected static function toUTF8(string $text): string
    {
        $max = self::length($text);

        $utf = "";
        for ($i = 0; $i < $max; $i++) {
            $c1 = $text[$i];

            // Should be converted to UTF-8, if it's not UTF-8 already
            if ($c1 >= "\xc0") {
                $c2 = $i + 1 >= $max ? "\x00" : $text[$i + 1];
                $c3 = $i + 2 >= $max ? "\x00" : $text[$i + 2];
                $c4 = $i + 3 >= $max ? "\x00" : $text[$i + 3];

                $isC2UTF8 = ($c2 >= "\x80" && $c2 <= "\xbf");
                $isC3UTF8 = ($c3 >= "\x80" && $c3 <= "\xbf");
                $isC4UTF8 = ($c4 >= "\x80" && $c4 <= "\xbf");

                // 2 bytes UTF-8
                if ($c1 >= "\xc0" & $c1 <= "\xdf") {
                    if ($isC2UTF8) {
                        $utf .= $c1 . $c2;
                        $i++;
                    } else {
                        $utf .= self::convertNonUtf8($c1);
                    }
                    // 3 bytes UTF-8
                } elseif ($c1 >= "\xe0" & $c1 <= "\xef") {
                    if ($isC2UTF8 && $isC3UTF8) {
                        $utf .= $c1 . $c2 . $c3;
                        $i = $i + 2;
                    } else {
                        $utf .= self::convertNonUtf8($c1);
                    }
                    // 4 bytes UTF-8
                } elseif ($c1 >= "\xf0" & $c1 <= "\xf7") {
                    if ($isC2UTF8 && $isC3UTF8 && $isC4UTF8) {
                        $utf .= $c1 . $c2 . $c3 . $c4;
                        $i = $i + 3;
                    } else {
                        $utf .= self::convertNonUtf8($c1);
                    }
                } else {
                    $utf .= self::convertNonUtf8($c1);
                }
            } elseif (($c1 & "\xc0") == "\x80") {
                // Windows-1252 case
                $utf .= self::$win1252ToUtf8[\ord($c1)] ?? self::convertNonUtf8($c1);
            } else {
                $utf .= $c1;
            }
        }

        return $utf;
    }

    /**
     * Get the number of characters of a multibyte string
     * using 8-bit encoding
     *
     * @param string $text
     * @return int
     */
    public static function length(string $text): int
    {
        return \mb_strlen($text, '8bit');
    }

    /**
     * Decode a UTF-8 string
     *
     * @param string $text
     * @return string
     */
    protected static function utf8Decode(string $text): string
    {
        $text = \str_replace(
            \array_keys(self::$brokenUtf8ToUtf8),
            \array_values(
                self::$brokenUtf8ToUtf8
            ),
            self::toUTF8($text)
        );

        return \mb_convert_encoding($text, "UTF-8", \mb_detect_encoding($text));
    }

    /**
     * Convert non UTF-8 character
     *
     * @param string $char
     * @return string
     */
    protected static function convertNonUtf8(string $char): string
    {
        $cc1 = (\chr(\ord($char) / 64) | "\xc0");
        $cc2 = ($char & "\x3f") | "\x80";

        return $cc1 . $cc2;
    }
}