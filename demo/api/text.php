<?php
$output_type = "json";
$text = $_POST['text'];
$Data['content'] = $text;
$temp = $Data['content'];
$Data['text'] = strip_tags($temp,"<p>");

//分词 提取关键词
$sh = scws_open();
scws_set_charset($sh, 'utf8');
scws_set_dict($sh, 'C:\scws\etc\dict.utf8.xdb');
scws_set_rule($sh, 'C:\scws\etc\rules.ini');
scws_add_dict($sh, 'C:\scws\etc\dict_extra.txt',SCWS_XDICT_TXT);
$text = strip_tags($temp);
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

$Data['elements'] = array('noun'=>$noun,'nz'=>$nz,'vn'=>$vn,'nt'=>$nt,'ns'=>$ns,'nr'=>$nr,'names'=>$names);
	
//最后输出
switch($output_type) {
    case 'json':
        header("Content-type: text/json;charset=utf-8");
        echo json_encode($Data,JSON_UNESCAPED_UNICODE);
        break;

    case 'html': default:
        header("Content-type: text/html;charset=utf-8");

        $title   = $Data['title'];
        $content = $Data['content'];

}
