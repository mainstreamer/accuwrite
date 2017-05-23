<?php

class ArrayManager
{
    private $frequency;

    public $dictionary = [];

    public function __construct()
    {
        $this->dictionary = file('words.txt', FILE_IGNORE_NEW_LINES);
        $this->dictionary = array_flip($this->dictionary);
    }

    public function exists(string $word):bool
    {
        return isset($this->dictionary[$word]);
    }
}