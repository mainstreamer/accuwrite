<?php declare(strict_types=1);

/**
 * Class TrieManager
 */

class TrieManager
{
    /**
     * @var array|mixed
     */
    private $frequency = [];

    /**
     * @var array
     */
    public $trie = [];

    /**
     * TrieManager constructor.
     */
    public function __construct()
    {
        
        if (file_exists('frequency')) {
            $this->frequency = unserialize(file_get_contents('frequency'));
        }
        
        if (!file_exists('triedb') && file_exists('words.txt')) {

            $array = file('words.txt', FILE_IGNORE_NEW_LINES);

            foreach ($array as $word) {
                $this->addWord($word);
            }
            
            $this->save();
        }
    }

    /**
     * @param string $prefix
     * @return array
     */
    public function getNodeByPrefix(string $prefix) : array
    {
        $node = $this->trie;
        $prefix = $this->splitWord($prefix);

        for ($i=0; $i<count($prefix); $i++) {
            $node = $node[$prefix[$i]];
        }
        
        return $node;
    }

    /**
     * @param string $word
     * @return array
     */
    private function splitWord(string $word) : array
    {
        return preg_split('//u', $word, -1, PREG_SPLIT_NO_EMPTY);
    }

    /**
     * @param string $word
     */
    public function addWord(string $word) : void
    {
        $word = $this->purify($word);
        $arrayOfLetters = $this->splitWord($word);
        $currentNode = &$this->trie;

        for ($i = 0; $i<count($arrayOfLetters); $i++) {
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

    /**
     * @param string $word
     * @return bool
     */
    public function searchWord(string $word) : bool
    {
        $word = $this->purify($word);
        $arrayOfLetters = $this->splitWord($word);
        $currentNode = &$this->trie;

        for ($i = 0; $i < count($arrayOfLetters); $i++) {
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

        return false;
    }

    /**
     * @param $word
     * @return bool
     */
    public function exists($word) : bool
    {
        return $this->searchWord($word);
    }

    /**
     * @param array $array
     * @return string
     */
    public function arrayToString(array $array) : string
    {
        $string = '';
        foreach ($array as $letter) {
            $string.=$letter;
        }
        
        return $string;
    }


    /**
     * @param string $prefix
     * @return array
     */
    public function getValidChildren(string $prefix) : array
    {
        $node = $this->getNodeByPrefix($prefix);
        $response = [];
        foreach ($node as $letter => $subnode) {
            if ($subnode['valid']) {
                $response[] = $prefix.$letter;
            }
        }

        return $response;
    }

    /**
     * @param $node
     * @param $child
     * @return bool
     */
    public function hasChild($node, $child) : bool
    {
        $child = $this->splitWord($child);
        for ($i=0; $i < count($child); $i++) {
            if (isset($node[$child[$i]])) {
                $node = $node[$child[$i]];
            } else {
                return false;
            }
            if ($i == count($child)-1 ) {

                if ($node['valid']) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * @param array $node
     * @return bool
     */
    public function hasChildren(array $node) : bool
    {
        return count($node) > 1 ? true : false;

    }

    /**
     * @param string $prefix
     * @return array
     */
    public function getPotentiallyValidChildren(string $prefix) : array
    {
        $node = $this->getNodeByPrefix($prefix);
        $response = [];
        foreach ($node as $letter => $subnode) {
            if ($letter == 'valid') {
                continue;
            }
            if ($this->hasChildren($subnode)) {
                $response[] = $prefix.$letter;
            }
        }

        return $response;
    }

    /**
     * @param string $string
     * @return string
     */
    protected function purify(string $string): string
    {
        return addslashes(trim(mb_strtolower($string)));
    }

    /**
     * @param string $location
     * @return void
     */
    public function save(string $location = 'triedb') : void
    {
        file_put_contents("$location", serialize($this));
    }
}