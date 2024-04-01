<?php

declare(strict_types=1);

namespace App\Utils;

/**
 * 和暦・西暦の変換モジュール
 */
class JapaneseEraConverter
{
    /**
     * 日本の元号リストを降順で保持する配列。
     *
     * @var array[] $japaneseEraList 
     * @var int $japaneseEraList['year'] 元号の開始年。
     * @var string $japaneseEraList['name'] 元号の名前。
     */
    private static $japaneseEraList = [
        ['year' => 2019, 'name' => '令和'],
        ['year' => 1989, 'name' => '平成'],
        ['year' => 1926, 'name' => '昭和'],
        ['year' => 1912, 'name' => '大正'],
        ['year' => 1868, 'name' => '明治']
    ];

    /**
     * 元号名リストを取得する関数
     *
     * @return array
     */
    public static function getJapaneseEraNameList(): array
    {
        return array_column(self::$japaneseEraList, 'name');
    }

    public static function getJapaneseEraStartYear($eraName):int
    {
        $index = array_search($eraName, array_column(self::$japaneseEraList, 'name'));

        if($index===false){
            return 0;
        }

        return self::$japaneseEraList[$index]['year'] ?? 0;
    }

    /**
     * 和暦から西暦に変換する関数
     *
     * @param string $year
     * @return string|null
     */
    public static function toWesternYear(string $year): string|null
    {
        $validationRule = function ($year) {
            return str_replace('元', '1年', mb_convert_kana($year, 'n'));
        };

        $japaneseEra = $validationRule($year);

        $eraStartYears = array_column(self::$japaneseEraList, 'year', 'name');
        $eraNames = implode('|', array_keys($eraStartYears));

        if (preg_match('!^(' . $eraNames . ')([0-9]+)年$!', $japaneseEra, $matches)) {
            $eraName = $matches[1];
            $year = intval($matches[2]) + $eraStartYears[$eraName] - 1;

            return $year . '年';
        }

        return null;
    }

    /**
     * 西暦から和暦に変換する関数
     *
     * @param string $year
     * @return string|null
     */
    public static function toJapaneseEra(string $year): string|null
    {
        $eras = self::$japaneseEraList;

        foreach ($eras as $era) {
            if ($year > $era['year']) {
                $eraYear = intval($year) - $era['year'] + 1;

                return $era['name'] . $eraYear . '年';
            }
        }

        return '西暦' . $year . '年';
    }

    /**
     * 元号と年の組み合わせ上ありえないものを正規の元号へ変換する
     * 元号が重複する年は、新元号を優先して採用する
     * 
     * @param string $year
     * @return string
     */
    public static function formatJapaneseEra(string $year): string
    {
        $westernYear = self::toWesternYear($year);
        $japaneseEra = self::toJapaneseEra($westernYear);

        return $japaneseEra;
    }
}
