<?php

/**
 * Dfa 算法，实现高效敏感词过滤
 */
class Dfa
{

    public $sensitiveWords = [];

    public function buildSensitiveTree($words)
    {
        foreach ($words as $word) {
            $tree = &$this->sensitiveWords;
            $wordLen = mb_strlen($word);
            for ($i = 0; $i < $wordLen; $i ++) {
                $w = mb_substr($word, $i, 1);
                if (!isset($tree[$w])) {
                    $tree[$w] = [
                        'end' => ($i == $wordLen - 1),
                    ];
                }

                $tree = &$tree[$w];
            }
        }
    }

    /**
     * 高亮敏感词
     * @param  string $txt 文本
     */
    public function highlight($txt)
    {
        $tempTxt = $txt;
        $contentLen = mb_strlen($txt);
        $sensitiveWordList = [];
        for ($start = 0; $start < $contentLen; $start ++) {
            $offset = 0;
            $flag = false;
            $tempMap = $this->sensitiveWords;
            for ($i = $start; $i < $contentLen; $i ++) {
                $word = mb_substr($txt, $i, 1);
                if (!isset($tempMap[$word])) {
                    break;
                }

                $offset ++;
                $tempMap = &$tempMap[$word];
                if ($tempMap['end']) {
                    $flag = true;
                    break;
                }
            }

            if ($flag) {
                $sensitiveWordList[] = mb_substr($txt, $start, $offset);
                $tempTxt = $this->substrReplaceCn($tempTxt, '*', $start, $offset);
                $start = $start + $offset - 1;
            }
        }
        echo $txt . "\n";
        echo $tempTxt . "\n";
        return $sensitiveWordList;
    }

    public function substrReplaceCn($string, $repalce = '*',$start = 0,$len = 0) {
        $count = mb_strlen($string, 'UTF-8'); // 此处传入编码，建议使用utf-8。此处编码要与下面mb_substr()所使用的一致
        if (!$count) {
            return $string;
        }

        if ($len == 0) {
            $end = $count;  // 传入0则替换到最后
        } else {
            $end = $start + $len; //传入指定长度则为开始长度+指定长度
        }

        $i = 0;
        $returnString = '';
        while ($i < $count) { //循环该字符串
            $tmpString = mb_substr($string, $i, 1, 'UTF-8'); // 与mb_strlen编码一致
            if ($start <= $i && $i < $end) {
                $returnString .= $repalce;
            } else {
                $returnString .= $tmpString;
            }
            $i ++;
        }
        return $returnString;
    }
}

$dfa = new Dfa;
$sensitiveKeywords = [
    '比例',
    '中国人',
    '中国男人',
    '中国女人'
];
$dfa->buildSensitiveTree($sensitiveKeywords);
$containsSensitiveWords = $dfa->highlight('中华人民中国人中国男人的比例为49%，少于中国女人');
var_dump($dfa->sensitiveWords);
var_dump($containsSensitiveWords);