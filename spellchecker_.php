<?php
error_reporting(0);
ini_set("display_errors", 0);
require __DIR__ . '/vendor/autoload.php';
$redis = new Predis\Client();


$alphabet = ["а","б","в","г","д","е","є","ж","з","і","ї","й","и","к","л","м","н","о","п","р","с","т","у","ф","х","ц","ч","ш","щ","ю","я","'"];
$ignored = [',','.','"','1','2','3','4','5','6','7','8','9','0','+','_','=',':','(',')',']','[',';','—'];
$candidates =  $response = [];

//use Predis\Collection\Iterator as Iterator;
$totaltime = microtime(true);

foreach ($_POST['text'] as $array) {
    $time = microtime(true);

    $value = mb_strtolower($array['value']);
    $value = str_replace($ignored,'',$value);
    if ($value == '-') {continue;}
//    $value = addslashes(trim($array['value']));
//    $value = $_POST['text'][0]['value'];
//    $value = utf8_encode(trim($array['value']));

    if (empty($value)) {
        continue;
    }

    if (!$redis->exists($value)) {
        //Here we are searching for a similar word
        $time = microtime(true);
        $word = preg_split('//u',$value,-1,PREG_SPLIT_NO_EMPTY);


        // check deletions
        foreach ($word as $key => $letter) {
            $tmpWord = $word; // сopy array of letters to tmp var
            unset($tmpWord[$key]); // swap previous and current letters
            $tmpWord = implode($tmpWord);

            if ($redis->exists($tmpWord)) {
                $value = $tmpWord;
                break;
                continue;
            }
        }

        // check insertions
        foreach ($word as $key => $letter) {
            foreach ($alphabet as $let) {

                $tmpWord = $word; // сopy array of letters to tmp var
                array_splice($tmpWord, $key, 0, $let); // add letter
                $tmpWord = implode($tmpWord);

                if ($redis->exists($tmpWord)) {
                    $value = $tmpWord;
                    break;
                    continue;

                }
            }

            if ($key == count($word)-1) {
                foreach ($alphabet as $let) {

                    $tmpWord = $word; // сopy array of letters to tmp var
                    array_push($tmpWord, $let); // add letter
                    $tmpWord = implode($tmpWord);

                    if ($redis->exists($tmpWord)) {
                        $value = $tmpWord;
                        break;
                        continue;
                    }
                }
            }
        }

        // check transpositions
        foreach ($word as $key => $letter) {
            if ($key > 0) {
                $tmpWord = $word; // сopy array of letters to tmp var
                $tmp = $tmpWord[$key]; // swap previous and current letters
                $tmpWord[$key] = $tmpWord[$key-1]; //
                $tmpWord[$key-1] = $tmp;
                $tmpWord = implode($tmpWord);
                if ($redis->exists($tmpWord)) {
                //$value = $tmpWord;
                $value = $tmpWord;
//                    $candidates[] = $tmpWord;
//                    break;continue;
                }
            }

        }


        // check substitution


        $time = microtime(true) - $time;

        $response[] = ['id' => $array['id'], 'value' => $value, 'time' => $time];
    } else {
    // so far better return nothing if word is correct

    }
}

function findSimilarWord($id, $value, $time = null) {
//    return
}


$response[] = ['total' => microtime(true)-$totaltime, 'words' => count($_POST['text'])];
print json_encode($response);