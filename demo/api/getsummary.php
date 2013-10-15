<?php
$post_data['text'] = $_POST['text'];
$post_data['percent'] = 0.2;
$post_data['username'] = "";
$post_data['password'] = "";
$url='http://as.zhichengtech.com/getSummary.php';
$o="";
foreach ($post_data as $k=>$v)
{
    $o.= "$k=".urlencode($v)."&";
}
$post_data=substr($o,0,-1);
$ch = curl_init();
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_URL,$url);
curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
$result = curl_exec($ch);
?>