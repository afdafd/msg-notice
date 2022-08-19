<?php

include "../vendor/autoload.php";
use Notice\Notice\NoticeFactory;

//
//$dindin = NoticeFactory::dinDin([
//  'webHookUrl' => 'https://oapi.dingtalk.com/robot/send?access_token=3d141b998d0439b72bb3b4fa8dbf3892b0e0ba374e612637de78ee3b1a7695ef'
//]);
//
//
////文本类型
//$dindin->text('报警：一个测试的消息');
////link 类型
//$dindin->link('报警：我是link', '我是一个测试的link', 'http://www.baidu.com');
//$dindin->markdown();
//$dindin->actionCard();
//$dindin->feedCard();


//NoticeFactory::wechat();
//NoticeFactory::sms();
//NoticeFactory::email();


$a = [
    "host" => "xxx",
    "port" => 666,
    "encryption" => "ssl",
    "from_address" => "xxx",
    "username" => "xxx",
    "password" => "xxx",
    "from_name" => "xxx"
];
NoticeFactory::email($a)->sendAttach(
    ['test@163.com'],
    '/test.docx',
    '哈哈哈哈_test'
);