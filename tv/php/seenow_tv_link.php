#!/usr/local/bin/Resource/www/cgi-bin/php
<?php
function str_between($string, $start, $end){ 
	$string = " ".$string; $ini = strpos($string,$start); 
	if ($ini == 0) return ""; $ini += strlen($start); $len = strpos($string,$end,$ini) - $ini; 
	return substr($string,$ini,$len); 
}
function enc($string) {
  $local3="";
  $arg1=strlen($string);
  $arg2="mediadirect";
  $l_arg2=strlen($arg2);
  $local4=0;
  while ($local4 < $arg1) {
    $m1=ord($string[$local4]);
    $m2=ord($arg2[$local4 % $l_arg2]);
    $local3=$local3.chr($m1 ^ $m2);
    $local4++;
  }
  return $local3;
}
//http://www.dolcetv.ro/tv-live-Mooz-RO-115?ajaxrequest=1
//$link = $_GET["file"];
$query = $_GET["file"];
if($query) {
   $queryArr = explode(',', $query);
   $id = urldecode($queryArr[0]);
   $buf = $queryArr[1];
}
$l="http://www.seenow.ro/service3/play/index/id/".$id."/platform_id/19";
$html = file_get_contents($l);
if (strpos($html,"stream") === false) {
$new_file="D://dolce.gz";
$new_file="/tmp/dolce.gz";
$fh = fopen($new_file, 'w');
fwrite($fh, $html);
fclose($fh);
$zd = gzopen($new_file, "r");
$html = gzread($zd, filesize($new_file));
gzclose($zd);
}
$html=str_replace("\\","",$html);
$t1=explode('high quality stream name":"',$html);
$t2=explode('"',$t1[1]);
$str=$t2[0];

//$t1=explode('application name":"',$html);
//$t2=explode('"',$t1[1]);
//$app=$t2[0];
$app="live3";

$t1=explode('token=',$html);
$t2=explode('|',$t1[1]);
$token=$t2[0];

$s="http://index.mediadirect.ro/getUrl?app=live3&file=".$str."&publisher=24";
$h = file_get_contents($s);
$t1=explode('server=',$h);
$t2=explode('&',$t1[1]);
$serv=$t2[0];
if ($serv == "") {
  $serv="fms1.mediadirect.ro";
}
//$buf="60000";
$rtmp="rtmp://".$serv."/".$app."/_definst_";
$l="Rtmp-options:-b ".$buf;
$l=$l." -a ".$app."/_definst_?&token=".$token." -W http://static1.mediadirect.ro/mediaplayer/players/0027/player.swf";
$l=$l." -p http://www.seenow.ro/ ";
$l=$l."-y ".$str;
$l=$l.",".$rtmp;
$l=str_replace(" ","%20",$l);
if ($token) print $l;
?>
