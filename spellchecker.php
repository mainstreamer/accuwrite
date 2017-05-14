<?php
require __DIR__ . '/vendor/autoload.php';

class Spellchecker
{
    private $redis;
    
    private $alphabet = ["а","б","в","г","д","е","є","ж","з","і","ї","й","и","к","л","м","н","о","п","р","с","т","у","ф","х","ц","ч","ш","щ","ю","я","'"];
    
    private $ignored = [',','.','"','1','2','3','4','5','6','7','8','9','0','+','_','=',':','(',')',']','[',';','—','«','»'];

    private $candidates = [];
    
    private $response = [];
    

    public function __construct($redis)
    {
        $this->redis=$redis;
    }
    
    private function checkDeletions($word)
    {
        $value = null;
        foreach ($word as $key => $letter) {
            $tmpWord = $word; // сopy array of letters to tmp var
            unset($tmpWord[$key]); // swap previous and current letters
            $tmpWord = implode($tmpWord);

            if ($this->redis->exists($tmpWord)) {
                $value = $tmpWord;
                break;
            }
        }   
        
        return $value;
    }
    
    private function checkInsertions($word)
    {
        $value = null;
        foreach ($word as $key => $letter) {
            foreach ($this->alphabet as $let) {

                $tmpWord = $word; // сopy array of letters to tmp var
                array_splice($tmpWord, $key, 0, $let); // add letter
                $tmpWord = implode($tmpWord);

                if ($this->redis->exists($tmpWord)) {
                    $value = $tmpWord;
                    break;
                }
            }

            if ($key == count($word)-1) {
                foreach ($this->alphabet as $let) {

                    $tmpWord = $word; // сopy array of letters to tmp var
                    array_push($tmpWord, $let); // add letter
                    $tmpWord = implode($tmpWord);

                    if ($this->redis->exists($tmpWord)) {
                        $value = $tmpWord;
                        break;
                    }
                }
            }
        }
        return $value;
    }
    
    private function checkTranspositions($word)
    {
        $value = null;
        foreach ($word as $key => $letter) {
            if ($key > 0) {
                $tmpWord = $word; // сopy array of letters to tmp var
                $tmp = $tmpWord[$key]; // swap previous and current letters
                $tmpWord[$key] = $tmpWord[$key-1]; //
                $tmpWord[$key-1] = $tmp;
                $tmpWord = implode($tmpWord);
                if ($this->redis->exists($tmpWord)) {
                    $value = $tmpWord;
                }
            }
        }
        
        return $value;
    }

    private function checkSubstitutions($word)
    {
        $value = null;
        foreach ($word as $key => $letter) {

            foreach ($this->alphabet as $let) {

                $tmpWord = $word; // сopy array of letters to tmp var
                $tmpWord[$key] = $let;
                $tmpWord = implode($tmpWord);

                if ($tmpWord!=$word && $this->redis->exists($tmpWord)) {
                    $value = $tmpWord;
                    break 2;
                }
            }
        }

        return $value;
    }

    public function processInput($input)
    {
        $totaltime = microtime(true); //benchmark

        foreach ($input as $array) {
            $time = microtime(true);
            $value = $this->sanitize($array);
            if ($value == '-' || empty($value)) {continue;} // ignore hyphens that are not between words
            if (!$this->redis->exists($value)) {
                $value = $this->splitWord($value);

                $response = $this->checkTranspositions($value);

                if (!$response) {
                    $response = $this->checkDeletions($value);
                }
                if (!$response) {
                    $response = $this->checkInsertions($value);
                }
                if (!$response) {
                    $response = $this->checkSubstitutions($value);
                }

                $response = $response ? $response : 'underline';

                $this->response[] = ['id' => $array['id'], 'value' => $response, 'time' => microtime(true) - $time];
            }
        }

        // some statistics
        $this->response[] = ['total' => microtime(true)-$totaltime, 'words' => count($_POST['text'])];
        $this->output();
    }

    private function sanitize(array $data)
    {
        $value = mb_strtolower($data['value']);
        $value = str_replace($this->ignored,'',$value);

        return $value;
    }

    private function splitWord(string $word)
    {
        return preg_split('//u',$word,-1,PREG_SPLIT_NO_EMPTY);
    }

    private function output($response = null)
    {
        print json_encode($this->response);
    }
}

$redis = new Predis\Client();
$spellchecker = new Spellchecker($redis);
$spellchecker->processInput($_POST['text']);