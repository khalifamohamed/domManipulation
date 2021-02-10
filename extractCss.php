<?php
$str = "https://qor360.com";
echo saveCssLinks($str);

function saveCssLinks($str)
{
    $arr_links = array();
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $str);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // This  is the part where we will load the html link
    $html = curl_exec($ch);

    $dom = new DOMDocument();
    @$dom->loadHTML($html);

    $links_tags = $dom->getElementsByTagName('link'); // Returns all tagName with <link>

    // foreach Loop to identify the stylesheet attribute and then get its hyper-reference
    foreach ($links_tags as $link)
    {
        if ($link->getAttribute('rel') == "stylesheet" || $link->getAttribute('rel') == 'preload') // Hi, Abdelalim.
        {
            $url_link = $link->getAttribute('href');
            if (isCss($url_link))
            {
                $url_link = $link->getAttribute('href');
                if (!checkUrl($url_link))
                {
                    $url_link = $str . $url_link;
                    array_push($arr_links, $url_link);
                }
                else
                {
                    array_push($arr_links, $url_link);
                }
            }
        }
    }
    return appendToString($arr_links);
}
// Function that checks whether a given url has a host (eg. facebook.com) or not.
function checkUrl($url)
{
    $urlParse = parse_url($url);
    if (array_key_exists('host', $urlParse)) return true;
    else return false;
}

// Function that checks whether a file is Css or not.
function isCss($url)
{
    if (substr($url, -4) === '.css')
    {
        return true;
    }
    else
    {
        return false;
    }
}

// This function main role is to take an array of links as a parameters and get the contents of every link, append them to the newCss.css file
// and voila.
function appendToString($arr){
    $str = null;
    foreach($arr as $cssLink){
    $str .= file_get_contents($cssLink);
    }
    return $str;
}
?>