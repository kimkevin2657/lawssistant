<?php
include_once('./_common.php');
ob_end_clean();
$file = $_GET['file']; // 화일이 실제로 있는 위치를.. 
$filename = $_GET['url'];
$bo_table = $_GET['mid'];

$filepath = MS_DATA_PATH.'/board/'.$bo_table.'/'.$file;
$filepath = addslashes($filepath);
//echo $filepath;
//exit;
if (!is_file($filepath) || !file_exists($filepath)) { alert('파일이 존재하지 않습니다.'); }

$original = urlencode($file);

if(preg_match("/msie/i", $_SERVER['HTTP_USER_AGENT']) && preg_match("/5\.5/", $_SERVER['HTTP_USER_AGENT'])) {
    header("content-type: doesn/matter");
    header("content-length: ".filesize("$filepath"));
    header("content-disposition: attachment; filename=\"$original\"");
    header("content-transfer-encoding: binary");
} else if (preg_match("/Firefox/i", $_SERVER['HTTP_USER_AGENT'])){
    header("content-type: file/unknown");
    header("content-length: ".filesize("$filepath"));
    header("content-disposition: attachment; filename=\"".basename($file)."\"");
    header("content-description: php generated data");
} else {
    header("content-type: file/unknown");
    header("content-length: ".filesize("$filepath"));
    header("content-disposition: attachment; filename=\"$original\"");
    header("content-description: php generated data");
}
header("pragma: no-cache");
header("expires: 0");
flush();

$fp = fopen($filepath, 'rb');

$download_rate = 10;

while(!feof($fp)) {
    print fread($fp, round($download_rate * 1024));
    flush();
    usleep(1000);
}
fclose ($fp);
flush();
?>