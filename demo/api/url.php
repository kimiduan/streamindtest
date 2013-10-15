<?php
// vim: set et sw=4 ts=4 sts=4 ft=php fdm=marker ff=unix fenc=utf8 nobomb:
/**
 * PHP Readability
 *
 * @author mingcheng<i.feelinglucky#gmail.com>
 * @date   2011-02-17
 * @link   http://www.gracecode.com/
 */

require 'config.inc.php';
require 'common.inc.php';
require 'lib/Readability.inc.php';

//$request_url = getRequestParam("url",  "");
$request_url = $_POST['url'];
$output_type = strtolower(getRequestParam("type", "json"));

// 如果 URL 参数不正确，则跳转到首页
if (!preg_match('/^http:\/\//i', $request_url) ||
    !filter_var($request_url, FILTER_VALIDATE_URL, FILTER_FLAG_SCHEME_REQUIRED)) {
    include 'template/index.html';
    exit;
}

$request_url_hash = md5($request_url);
$request_url_cache_file = sprintf(DIR_CACHE."/%s.url", $request_url_hash);

// 缓存请求数据，避免重复请求
if (file_exists($request_url_cache_file) && 
        (time() - filemtime($request_url_cache_file) < CACHE_TIME)) {

    $source = file_get_contents($request_url_cache_file);
} else {
    $handle = curl_init();
    curl_setopt_array($handle, array(
	    CURLOPT_USERAGENT => USER_AGENT,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HEADER  => false,
        CURLOPT_HTTPGET => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_URL => $request_url
    ));

    $source = curl_exec($handle);
    curl_close($handle);

    // Write request data into cache file.
    file_put_contents($request_url_cache_file, $source);
}

// 判断编码
//if (!$charset = mb_detect_encoding($source)) {
//}
preg_match("/charset=([\w|\-]+);?/", $source, $match);
$charset = isset($match[1]) ? $match[1] : 'utf-8';

/**
 * 获取 HTML 内容后，解析主体内容
 */
$Readability = new Readability($source, $charset);
$Data = $Readability->getContent();

$temp = $Data['content'];
$Data['text'] = strip_tags($temp,"<p>");

//分词 提取关键词
$sh = scws_open();
scws_set_charset($sh, 'utf8');
scws_set_dict($sh, 'C:\scws\etc\dict.utf8.xdb');
scws_set_rule($sh, 'C:\scws\etc\rules.ini');
scws_add_dict($sh, 'C:\scws\etc\dict_extra.txt',SCWS_XDICT_TXT);
$text = strip_tags($temp);
scws_send_text($sh, $Data['title']);
$keywords = array();
$top_keywords = scws_get_tops($sh, 100);
while ($res = scws_get_result($sh))
    {
        foreach ($res as $tmp)
        {
		  if(in_array($tmp['attr'],array("n","ns","nz","nt","vn","nr","en"))){
            $keywords = array_merge_recursive($keywords,array($tmp));
			}
        }
    }

scws_send_text($sh, $text);
$top = scws_get_tops($sh, 100);
$num = count($top);
$noun = array();
$vn = array();
$nr = array();
$nz = array();
$ns = array();
$nt = array();
$en = array();
$judge = array("n","ns","nz","nt","vn","nr","en");
//$count = array('0'=>'one','1'=>'two','2'=>'three','3'=>'four','4'=>'five','5'=>'six','6'=>'seven','7'=>'eight','8'=>'nine','9'=>'ten');
for($i=0;$i<$num;$i++){
		switch($top[$i]['attr']){
			case "n" : $noun=array_merge_recursive($noun,array($top[$i])); break;
			case "nz": $nz=array_merge_recursive($nz,array($top[$i])); break;
			case "vn": $vn=array_merge_recursive($vn,array($top[$i])); break;
			case "nt": $nt=array_merge_recursive($nt,array($top[$i])); break;
			case "ns": $ns=array_merge_recursive($ns,array($top[$i])); break;
			case "nr": $nr=array_merge_recursive($nr,array($top[$i])); break;
			case "en": {if(!is_numeric($top[$i])){$en=array_merge_recursive($en,array($top[$i]));}; break;}
			defult : ;
			} 
	}	
	
$names = array();
  function get_names($text,$names){
  $start = strpos($text,"《");
  $end = strpos($text,"》");
  $names = array_merge_recursive($names,array(substr($text,$start,$end-$start+3)));
  //echo substr($text,$start,$end-$start+3);
  // print_r($names);
  $text = substr($text,$end+3);
  if(strpos($text,"《")>0){
    $names = get_names($text,$names);
  }
  return $names;
  }
  $names = get_names($text,$names);	

$Data['elements'] = array('noun'=>$noun,'nz'=>$nz,'vn'=>$vn,'nt'=>$nt,'ns'=>$ns,'nr'=>$nr,'names'=>$names,'keywords'=>$keywords);
	
//最后输出
switch($output_type) {
    case 'json':
        header("Content-type: text/json;charset=utf-8");
        $Data['url'] = $request_url;
        echo json_encode($Data,JSON_UNESCAPED_UNICODE);
        break;

    case 'html': default:
        header("Content-type: text/html;charset=utf-8");

        $title   = $Data['title'];
        $content = $Data['content'];
		print_r($Data);

        //include 'template/reader.html';
}
