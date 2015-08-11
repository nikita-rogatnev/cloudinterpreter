<!DOCTYPE html>
<html style="height: 100%;">
<head>
    <title></title>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
</head>
<body>

<?php
/**
 * Created by PhpStorm.
 * User: kosmos
 * Date: 6/25/14
 * Time: 8:34 PM
 */



function get_site_texts($base_url) {

    $file['content'] = '';
    $mainURL = $base_url;

    if(getMainUrl($mainURL)) {
        $rootURL = getMainUrl($mainURL);
    }
    else {
        $rootURL = $base_url;
    }

    //echo $base_url; die;

    //$uniq_text = array();

    $visited_pages = array();
    $html = @file_get_contents($base_url);

    if($html) {

        array_push($visited_pages, $base_url);

        $html = removeJSCSS($html);

        $text[$mainURL] = extract_text($html);


        //yield $text;
        //yield $text[$mainURL];
        //$file['content'] .= showResult($text[$mainURL],$mainURL, $uniq_text);


        //yield $visited_pages; //uncomment this string to see links

        //$uniq_text = addUniqText($uniq_text,$text[$mainURL]);

        $urls = array();


        $urls = extract_urls($html, $mainURL, $rootURL, $visited_pages);


//        echo "<pre>";
//        print_r($urls); die;

        //$urls = array_unique($urls);

        while($urls) {
            $url = array_shift($urls);

            if (strpos($url,$mainURL) !== false && !(in_array($url,$visited_pages)) && checkContentType($url)) {
                $html = @file_get_contents($url);
                if($html) {
                    array_push($visited_pages, $url);

                    //yield $visited_pages; //uncomment this string to see links

                    $html = removeJSCSS($html);
                    $text[$url] = extract_text($html);

                    //$file['content'] .= showResult($text[$url],$url,$uniq_text);

                    //$uniq_text = addUniqText($uniq_text,$text[$url]);

                    //yield $text[$url];

                    $extracted_urls = extract_urls($html,$url, $rootURL, $visited_pages);
                    //$urls = my_array_push($urls,$extracted_urls);

                    foreach($extracted_urls as $k=>$v) {
                        if(!in_array($v,$urls) && !in_array($v,$visited_pages))
                            $urls[] = $v;
                    }

    //                $urls = array_unique($urls);
                    //$visited_pages = array_merge(array_unique($visited_pages));

    //                if($url == 'http://www.effectiff.com/articles') {
    //
    //                    echo "<pre>";
    //                    print_r($urls); die;
    //                }

                }
            }

            else {
               // if(checkContentType($url)) {
                //if(strpos($url,$mainURL) !== false) {
                //    array_push($visited_pages, $url);
                //    $visited_pages = array_merge(array_unique($visited_pages));
                //}
            }
        }


    }
    //$text = array_unique($text);

    return $text;


}

function checkContentType($url) {
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $type = @$finfo->buffer(file_get_contents($url));

    if($type == 'text/html') {
        return true;
    }
    else {
        return false;
    }
}

function addUniqText($uniq_text,$array) {

    foreach($array as $k=>$v) {
        if(!in_array($v,$uniq_text))
            $uniq_text[] = $v;
    }

    return $uniq_text;
}

function removeJSCSS($html) {
    $patterns  =  array("/<script.+?<\\/script\s*>/si", "/<!--.+?-->/", "/<style.+?<\\/style\s*>/si", "/<noscript.+?<\\/noscript\s*>/i", "/<link.+?\>/i");
    $html = preg_replace($patterns,"",$html);

    return $html;
}

function extract_text($html) {

    //$pattern = "/\>(.+)\</";
    //$pattern = "/>([^<]+)/";
    //$pattern = "/>([^<]+)</";
    $pattern = "/>\s*([^>]+?)\s*</";
    preg_match_all ( $pattern , $html, $out_array);

    $array = $out_array[1];

    $newArray = array();
    foreach($array as $k=>$v) {
        if(trim($v) && !in_array(trim($v), $newArray)) {
            $newArray[] = trim($v);
        }
    }

    return $newArray;
}

function getMainUrl($url) {

    $pattern = "/(http:\/\/.*)\//";


    preg_match($pattern,$url,$array);

    if(isset($array[1]) && $array[1]) {
        return $array[1];
    }
    else {
        return false;
    }

}

function removeNoFollowLinks($out_array) {

    $array = $out_array[3];
    $array_main = $out_array[2];

    for($i=0;$i<count($array);$i++) {

        if(strpos($array[$i],'rel="nofollow"') !== false || strpos($array[$i],"rel='nofollow'") !== false || strpos($array_main[$i],"/embed/quick/") !== false ) {
            unset($out_array[2][$i]);
        }
    }

    return array_merge(array_unique($out_array[2]));
}


function extract_urls($html, $mainURL, $rootURL, $visited_pages) {

    $pattern = "/(href|HREF)=[\"|\'](.*)[\n|\r|\"|\'](.*)\>/iU";
    //$pattern = "/href\s*=\s*("[^"]+"|'[^\']+'|[^>\/\s]+)/i";

    //$pattern = "/href\s*=\s*("[^"]+\"|'[^']+'|[^>\/\s]+)/i";
    
    preg_match_all ( $pattern , $html, $out_array);

    $array = removeNoFollowLinks($out_array);

    $newArray = array();

    for($i=0;$i<count($array);$i++) {


        if(substr($array[$i],strlen($array[$i])-1,1) == '/' && strpos($array[$i],'#') === false) {
            $array[$i] = substr($array[$i],0,strlen($array[$i])-1);
        }

        if (strpos($array[$i],'.png') !== false && preg_match ("/http\:.*\:.*/" , $array[$i])) {

        }
        elseif(substr($array[$i],0,1) == '/' && strpos($array[$i],'#') === false && !preg_match ("/.*\:.*/" , $array[$i])) {
                $newArray[] = $rootURL.$array[$i];
        }
        elseif (strpos($array[$i],'javascript:') !== false) {
            //unset($array[$i]);
        }
        elseif (strpos($array[$i],'mailto:') !== false) {
            //unset($array[$i]);
        }
        elseif (strpos($array[$i],'skype:') !== false) {
            //unset($array[$i]);
        }
        elseif (strpos($array[$i],'#') !== false) {

        }


        elseif($array[$i] && $array[$i] != '/'
            && substr($array[$i],0,1) != '/'
            && strtolower(substr($array[$i],0,4)) != 'http'
            && !preg_match ("/.*\:.*/" , $array[$i])
            ) {
                $newArray[] = $rootURL.'/'.$array[$i];
        }

        elseif(trim($array[$i])) {
                $newArray[] = $array[$i];
        }

    }

    $newArray = array_merge(array_unique($newArray));
    $newArray = array_diff($newArray, $visited_pages);
    
    return $newArray;
}

function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
}


//function showResult($array,$url, $uniq_text) {

//    $content = '';
//
//    $content .= "\n\n";

//    $fontStyle = new \PhpWord\Style\Font();
//    $fontStyle->setColor('7163f8');
//
//    $myTextElement = $section->addText($url);
//    $myTextElement->setFontStyle($fontStyle);

    //$content .=  $url."\n\n";

//    foreach($array as $k=>$v) {
//        if(in_array($v,$uniq_text)) {
//            $v;
//        }
//        else {
//            $fontStyle->setColor('000000');
//            $myTextElement = $section->addText($v);
//            $myTextElement->setFontStyle($fontStyle);
//        }
//    }

//    return $myTextElement;
//}



$site_name = $_REQUEST['siteName'];

if(substr($site_name,strlen($site_name)-1,1) == '/') {
    $site_name = substr($site_name,0,strlen($site_name)-1);
}

$page_text = get_site_texts($site_name);


//$page_text = get_site_texts('http://www.protek-upakovka.ru/catalog/8');

//
//foreach($page_text as $value) { //uncomment this string to see links
//    echo "<pre>";
//    print_r($page_text);
//}


//$array = array();

//$array['http://www.podzemgazprom.ru'] = $page_text['http://www.podzemgazprom.ru'];
//
//$array['http://www.podzemgazprom.ru/index.php?lang=rus&id=1'] = $page_text['http://www.podzemgazprom.ru/index.php?lang=rus&id=1'];

//$page_text = $array;

//echo "<pre>";
//print_r($page_text); die;


use PhpOffice\PhpWord\Autoloader;
use PhpOffice\PhpWord\Settings;
use PhpOffice\PhpWord\IOFactory;

error_reporting(E_ALL);
define('CLI', (PHP_SAPI == 'cli') ? true : false);
define('EOL', CLI ? PHP_EOL : '<br />');
define('SCRIPT_FILENAME', basename($_SERVER['SCRIPT_FILENAME'], '.php'));
define('IS_INDEX', SCRIPT_FILENAME == 'index');

require_once __DIR__ . '/src/PhpWord/Autoloader.php';
Autoloader::register();
Settings::loadConfig();

$phpWord = new \PhpOffice\PhpWord\PhpWord();
$section = $phpWord->addSection();

$uniq_text = array();


$limit = 0;
if(count($page_text)) {
    foreach ($page_text as $k=>$v) {

        $section->addTextBreak(2);
        // Add hyperlink elements
        //$section->addLink($k, $k, array('color'=>'0000FF', 'underline' => \PhpOffice\PhpWord\Style\Font::UNDERLINE_SINGLE));

//      $phpWord->addLinkStyle('myOwnLinkStyle', array('bold'=>true, 'color'=>'0000FF'));
//      $section->addLink($k, null, 'myOwnLinkStyle');

        //$section->addLink($k, $k, array('color'=>'0000FF', 'underline' => \PhpOffice\PhpWord\Style\Font::UNDERLINE_SINGLE));
        $section->addText('['.htmlspecialchars_decode($k).']', array('color'=>'0000FF'));

        $section->addTextBreak(1);

        foreach($v as $n=>$m) {
            if(in_array($m, $uniq_text)) {
                $section->addText(htmlspecialchars_decode($m), array('color'=>'f6e131','italic'=>true));
            }
            else {
                $section->addText(htmlspecialchars_decode($m));

                if(!in_array($m,$uniq_text))
                    $uniq_text[] = $m;
            }
            $section->addTextBreak(1);
        }
        $limit++;
    }

    $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');

    $doc_name = generateRandomString();
    $objWriter->save($doc_name.'.docx');

    echo "<a href='".$doc_name.".docx'>скачать документ</a>";

}
else {
    echo "текст не найден";
}

echo "<br /><br /><a href='index.html'>вернуться назад</a>";


    $count_words = 0;
    $count_symbols = 0;

    if(count($uniq_text)) {
        for($i=0;$i<count($uniq_text);$i++) {

            $count_words = $count_words + str_word_count(htmlspecialchars_decode($uniq_text[$i]));
            $count_symbols = $count_symbols + strlen(htmlspecialchars_decode($uniq_text[$i]));
        }
    }
?>


<br />
<br />


<table cellpadding="10" cellspacing="5" style="border: 1px solid grey;">
    <tr>
        <td>название сайта:</td>
        <td><?php echo $site_name;?></td>
    </tr>

    <tr>
        <td>количество найденных слов:</td>
        <td><?php echo $count_words;?></td>
    </tr>
    <tr>
        <td>количество знаков с пробелами:</td>
        <td><?php echo $count_symbols;?></td>
    </tr>
    <tr>
        <td>количество учётных страниц:</td>
        <td><?php echo ceil($count_symbols/1800);?></td>
    </tr>
    <?php if(isset($doc_name)) {?>
    <tr>
        <td>отчётный документ:</td>
        <td><? echo "<a href='".$doc_name.".docx'>скачать</a>";?></td>
    </tr>
    <?php }?>
</table>

</body>
</html>