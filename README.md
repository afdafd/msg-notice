#### 可以发送微信通知、钉钉通知、邮箱通知、短信通知等...

````php
<?php

include "../vendor/autoload.php";
use Notice\Notice\NoticeFactory;

//发送钉钉消息示例
NoticeFactory::dinDin([
  'webHookUrl' => ''
])->text('报警：一个测试的消息');

//NoticeFactory::wechat(); 微信通知
//NoticeFactory::sms();    短信通知
//NoticeFactory::email();  邮件通知
````
- 目前暂时接入了钉钉通知，后续会逐步完善其他几个