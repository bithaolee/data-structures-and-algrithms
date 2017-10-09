<?php


class Trie
{
    public $root;

    public function __construct()
    {
        $this->root = new Node();
    }

    public function addWord($word)
    {
        $current = $this->root;
        $len = strlen($word);
        for ($i = 0; $i < $len; $i ++) {
            $edge = substr($word, $i, 1);
            if (!isset($current->childrens[$edge])) {
                $current->childrens[$edge] = new Node();
                $current->childrens[$edge]->word = substr($word, 0, $i + 1);
            }

            $current = $current->childrens[$edge];
            if ($i == $len - 1) {
                $current->wordCount ++;
            }
        }
    }

    public function getWordCount($word)
    {
        $current = $this->root;
        $len = strlen($word);
        for ($i = 0; $i < $len; $i ++) {
            $edge = substr($word, $i, 1);
            if (!isset($current->childrens[$edge])) {
                return 0;
            }

            $current = $current->childrens[$edge];
            if ($i == $len - 1) {
                return $current->wordCount;
            }
        }   
    }
}

class Node
{

    public $word;

    public $wordCount = 0;

    /**
     * [
     *     'c' => new Node(),
     *     'f' => new Node(),
     *     ...
     * ]
     */
    public $childrens = [];
}

$trie = new Trie();
$trie->addWord('hello');
$trie->addWord('hellojack');
$trie->addWord('hello');
$trie->addWord('haolee');
$trie->addWord('welcome');
$trie->addWord('here');

echo $trie->getWordCount('hello');
echo $trie->getWordCount('haolee');
echo $trie->getWordCount('program');
