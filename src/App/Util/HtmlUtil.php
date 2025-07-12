<?php

namespace App\Util;

class HtmlUtil
{
    /**
     * HTMLエスケープを行う
     *
     * @param ?string $value エスケープ対象の文字列
     * @return string エスケープ後の文字列
     */
    public static function escape(?string $value): string
    {
        return is_null($value) ? "" : htmlspecialchars($value);
    }
}