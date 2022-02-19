<?php

include "../vendor/autoload.php";
use Notice\Notice\NoticeFactory;

NoticeFactory::dinDin([
  'webHookUrl' => ''
])->text('报警：一个测试的消息');

//NoticeFactory::wechat();
//NoticeFactory::sms();
//NoticeFactory::email();


