<?php
require_once('RollingCurl.class.php');
require_once('AngryCurl.class.php');
require_once('functions.php');
include_once('simple_html_dom.php');

error_reporting(E_ALL | E_STRICT) ;
ini_set('display_errors', 'On');
ini_set('max_execution_time', 0);
ini_set('memory_limit', '128M');

// Заполнить эту переменную перед использыванием!
define('SOURCE', 'https://www.hudhomestore.com');
define('AC_DIR', dirname(__FILE__));
define('GLOBAL_LIMIT_PER_ACTION', 25);

echo "# STAGE 2 - PARSING URL<br />";
echo "# limit set to: ". GLOBAL_LIMIT_PER_ACTION ;
// Выбрать список Url которые еще не распарсены

$arrUrlNotParsingYet = getListNotParsingYet();

$AC = new AngryCurl('callback_function');
$AC->init_console();
# Importing proxy and useragent lists, setting regexp, proxy type and target url for proxy check
# You may import proxy from an array as simple as $AC->load_proxy_list($proxy array);
$AC->load_proxy_list(
    AC_DIR . DIRECTORY_SEPARATOR . 'proxy_list.txt',
    # optional: number of threads
    200,
    # optional: proxy type
    'http'
# optional: target url to check
//'http://google.com',
# optional: target regexp to check
// 'title>G[o]{2}gle'
);
$AC->load_useragent_list(AC_DIR . DIRECTORY_SEPARATOR . 'useragent_list.txt');

foreach($arrUrlNotParsingYet as $url){
    $AC->get($url);
    //addDtProces($id);// Помечаю что url обработан - добавляю текущее время
}

# Starting with number of threads = 200
$AC->execute(200);
# You may pring debug information, if console_mode is NOT on ( $AC->init_console(); )
//AngryCurl::print_debug();
# Destroying
unset($AC);
# Callback function example
function callback_function($response, $info, $request)
{
    if ($info['http_code'] !== 200) {
        AngryCurl::add_debug_msg(
            "->\t" .
            $request->options[CURLOPT_PROXY] .
            "\tFAILED\t" .
            $info['http_code'] .
            "\t" .
            $info['total_time'] .
            "\t" .
            $info['url']
        );
    } else {

        /*************** Stage#2 ParseHouse*******************************/
        $ArrHouses = ParsingURL($response, $info, $request); // To arr
        AddUrlToTableHouses($ArrHouses);// To Table Houses
        /*************** end *******************************/

        AngryCurl::add_debug_msg(
            "->\t" .
            $request->options[CURLOPT_PROXY] .
            "\tOK\t" .
            $info['http_code'] .
            "\t" .
            $info['total_time'] .
            "\t" .
            $info['url']
        );
    }

    return;
}