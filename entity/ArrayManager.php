<?php


class ArrayManager
{
    public $dictionary = [];
    public function __construct()
    {
        $this->dictionary = file('words.txt', FILE_IGNORE_NEW_LINES);
        $this->dictionary = array_flip($this->dictionary);
    }

    public function exists($word)
    {
        return isset($this->dictionary[$word]);
    }

}