<?php

include "../vendor/autoload.php";
use Notice\Notice\NoticeFactory;

NoticeFactory::dinDin([
  'webHookUrl' => 'https://oapi.dingtalk.com/robot/send?access_token=3d141b998d0439b72bb3b4fa8dbf3892b0e0ba374e612637de78ee3b1a7695ef'
])->text('报警：一个测试的消息', [15869020161]);

NoticeFactory::wechat();
NoticeFactory::sms();
NoticeFactory::email();


