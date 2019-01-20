快递系统

===============

token 机制

$token = md5(session("user.user_id").CONFIG('password'));
$user_id = session("user.user_id");


# 配置 config.php


'api_url' => '',

//统计URL
'analytics_api_url' => '',

//统计URL
'account_url' => '',


'accessKeyId'     => '',
'accessKeySecret' => '',
'endpoint'        => '',
'bucket'          => '',

