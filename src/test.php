<?php

include "../vendor/autoload.php";
use Notice\Notice\NoticeFactory;

$dindin = NoticeFactory::dinDin([
  'webHookUrl' => ''
]);

//文本类型
$dindin->text('报警：一个测试的消息');
//link 类型
$dindin->link('报警：我是link', '我是一个测试的link', 'http://www.baidu.com');
//$dindin->markdown();
//$dindin->actionCard();
//$dindin->feedCard();


//NoticeFactory::wechat();
//NoticeFactory::sms();
//NoticeFactory::email();


