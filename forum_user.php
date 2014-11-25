<?php
echo "<h1>Изменяем группы юзера...</h1>";
$path = pathinfo($_SERVER['REQUEST_URI']);
// 127.0.0.1/projects/joomla-samples-2x
$url = 'http://' . $_SERVER['HTTP_HOST'] . $path['dirname'] . '/';
$curl = curl_init();
$curl_options = array(
    CURLOPT_URL => $url."index.php?option=com_sample&task=change_user_group&user_id=" . $_GET['user_id'],
    CURLOPT_HEADER => true,
    CURLOPT_RETURNTRANSFER => true
);
curl_setopt_array($curl, $curl_options);
$result = curl_exec ($curl);
list($headers, $content) = explode("\r\n\r\n", $result, 2);
var_dump($content);
curl_close ($curl);