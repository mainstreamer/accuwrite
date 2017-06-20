<?php


class TrieManager
{
    private $frequency = [];
    
    public $trie = [];

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

    public function getNodeByPrefix(string $prefix) : array
    {
        $node = $this->trie;
        $prefix = $this->splitWord($prefix);

        for ($i=0; $i < count($prefix); $i++)
        {
            $node = $node[$prefix[$i]];
        }
        
        return $node;
    }

    private function splitWord (string $word) : array
    {
        return preg_split('//u',$word,-1,PREG_SPLIT_NO_EMPTY);
    }

    public function addWord(string $word)
    {
        $word = $this->purify($word);
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
        $word = $this->purify($word);
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

    public function arrayToString(array $array): string
    {
        $string = '';
        foreach ($array as $letter)
        {
            $string.=$letter;
        }
        
        return $string;
    }

    
    public function getValidChildren(string $prefix): array
    {
        $node = $this->getNodeByPrefix($prefix);
        $response = [];
        foreach ($node as $letter => $subnode)
        {
            if ($subnode['valid']) {
//                $response[] = $subnode;
                $response[] = $prefix.$letter;
            }
        }

        return $response;
    }

    public function hasChild($node, $child)
    {

        $child = $this->splitWord($child);
        for ($i=0; $i < count($child); $i++)
        {
            
            if (isset($node[$child[$i]]))
            {
                $node = $node[$child[$i]];
            } else { return false;}

            if ($i == count($child)-1 ) {

                if ($node['valid']) {
                    return true;
                }
            }

        }

        return false;
    }

    public function hasChildren(array $node):bool
    {
        return count($node) > 1 ? true : false;

    }

    public function getPotentiallyValidChildren(string $prefix): array
    {
        $node = $this->getNodeByPrefix($prefix);

        $response = [];
        foreach ($node as $letter => $subnode)
        {
            if ($letter == 'valid') { continue; }

            if ($this->hasChildren($subnode)) {
//                $response[$prefix.$letter] = $subnode;
                $response[] = $prefix.$letter;
            }
        }

        return $response;
    }

    protected function purify(string $string) : string
    {
        return addslashes(trim(mb_strtolower($string)));
    }

    public function save(string $location = 'triedb')
    {
        file_put_contents("$location", serialize($this));
    }
}