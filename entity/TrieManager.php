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
        for ($i=0; $i < strlen($prefix); $i++)
        {

        }
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

    public function getValidChildren(array $node): array
    {
        $response = [];
        foreach ($node as $letter => $subnode)
        {
            if ($node['valid']) {
                $response[] = $node;
            }
        }

        return $response;
    }

    public function hasChildren(array $node):bool
    {
        return count($node) > 2 ? true : false;

    }

    public function getPotentiallyValidChildren(array $node): array
    {
        $response = [];
        foreach ($node as $letter => $subnode)
        {
            if ($this->hasChildren($node)) {
                $response[] = $node;
            }
        }

        return $response;

    }

    public function getAllChildren(array $node): array
    {

    }

//    public function findNeighbours($word)
//    {
//        $arrayOfLetters = $this->splitWord($word);
//        $currentNode = &$this->trie;
//
////        count($this->trie);exit;
//        $candidates = [];
//        $prefix ='';
//
//        for ($i = 0; $i< count($arrayOfLetters)-1; $i++){
//        }
//
//        foreach ($currentNode as $letter => $node)
//        {
//            foreach ($node as $let => $nd){
//
//                if ($nd[$arrayOfLetters[2]][$arrayOfLetters[3]]) {
//
////                    $candidates[] = $prefix.$letter;
//                    $candidates[] = $let;
//                 }
//            }
//
////            if ($node['valid'] === true) {
////                $candidates[] = $prefix.$letter;
////            }
//        }
//
//        foreach ($candidates as $v)
//        {echo $v;}
////        echo count($candidates);
//    }

    public function getCurrentWord(array $arrayOfLetters) : string
    {
        $array = $arrayOfLetters;
        $val = '';
        if (is_array($array)){
//            $val.= key()
        }

        for ($i = 0; $i < count($arrayOfLetters)-1; $i++)
        {

        }
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