<?php


class TrieManager
{
    private $dictionary = [];
    public $trie = [];

    public function __construct()
    {
    }

    private function splitWord (string $word) : array
    {
        return preg_split('//u',$word,-1,PREG_SPLIT_NO_EMPTY);
    }

    public function addWord(string $word)
    {
        $word = addslashes(trim(mb_strtolower($word)));

        $arrayOfLetters = $this->splitWord($word);

        $currentNode = &$this->trie;

        for ($i = 0; $i < count($arrayOfLetters); $i++)
        {
            if (!isset($currentNode[$arrayOfLetters[$i]])) {
                $currentNode[$arrayOfLetters[$i]]['valid'] = false;
            }
            if ($i == count($arrayOfLetters)-1) {
                $currentNode[$arrayOfLetters[$i]]['valid'] = true;
            } else {
                $currentNode = &$currentNode[$arrayOfLetters[$i]];
            }
        }
    }

    public function searchWord(string $word)
    {
        $word = addslashes(trim(mb_strtolower($word)));
        $arrayOfLetters = $this->splitWord($word);

        $currentNode = &$this->trie;

        for ($i = 0; $i < count($arrayOfLetters); $i++)
        {
            if (!isset($currentNode[$arrayOfLetters[$i]])) {
                return false;
            } elseif ($i == count($arrayOfLetters)-1) {

                if ($currentNode[$arrayOfLetters[$i]]['valid'] == true) {
                    return true;
                } else {
                    return false;
                }
            }

            $currentNode = &$currentNode[$arrayOfLetters[$i]];
        }
    }

    public function exists($word)
    {
        return $this->searchWord($word);
    }

    public function save(string $location = 'trie')
    {
        file_put_contents("$location", serialize($this));
    }
}