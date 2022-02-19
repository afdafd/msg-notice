<?php declare(strict_types=1);

namespace Notice\Notice\DinDin;

use EasyWeChat\Kernel\Exceptions\Exception;
use Notice\Notice\HttpRequest;
use Notice\Notice\Notice;
use Notice\Notice\NoticeException;
use Notice\NoticeHttpRequestException;

/**
 * 钉钉通知
 *
 * @Link https://open.dingtalk.com/document/group/custom-robot-access
 * Class DinDinNotice
 * @package Notice\DinDin
 */
class DinDinNotice extends Notice
{
  /**
   * @var string 机器人 Webhook地址
   */
  protected $webhookUrl;


  public function __construct(array $config)
  {
    if (!isset($config['webHookUrl']) || empty($config['webHookUrl'])) {
      throw new NoticeException('发送钉钉消息通知失败，缺少webHookUrl');
    }

    $this->webhookUrl = $config['webHookUrl'];
  }

  /**
   * 发送文本消息
   *
   * @param string $content 消息内容
   * @param array $mobiles 被@人的手机号(在content里添加@人的手机号)
   * @param bool $isAtAll @所有人时：true，否则为：false
   * @return array
   */
  public function text(string $content, array $mobiles = [], bool $isAtAll = false): array
  {
    $data = [
      'msgtype' => 'text',
      'text' => [
        'content' => $content
      ],
      'at' => [
        'atMobiles' => $mobiles,
        'isAtAll' => $isAtAll
      ]
    ];

    return $this->sendMsgNotice($data);
  }

  /**
   * 发送链接消息
   *
   * @param string $title 消息标题
   * @param string $text 消息内容。如果太长只会部分展示
   * @param string $messageUrl 点击消息跳转的URL
   * @param string $picUrl 图片URL
   * @return array
   */
  public function link(string $title, string $text, string $messageUrl, string $picUrl = ''): array
  {
    $data = [
      'msgtype' => 'link',
      'link'    => [
        'title'       => $title,
        'text'        => $text,
        'messageUrl'  => $messageUrl,
        'picUrl'      => $picUrl,
      ]
    ];

    return $this->sendMsgNotice($data);
  }

  /**
   * 发送Markdown消息
   *
   * @param string $title 首屏会话透出的展示内容
   * @param string $text markdown格式的消息
   * @param array $mobiles 被@人的手机号(在content里添加@人的手机号)
   * @param bool $isAtAll @所有人时：true，否则为：false
   * @return array
   */
  public function markdown(string $title, string $text, array $mobiles = [], bool $isAtAll = false): array
  {
    $data = [
      'msgtype'  => 'markdown',
      'markdown' => [
        'title' => $title,
        'text'  => $text,
      ],
      'at' => [
        'atMobiles' => $mobiles,
        'isAtAll'   => $isAtAll
      ]
    ];

    return $this->sendMsgNotice($data);
  }

  /**
   * 发送ActionCard
   *
   * @param string $title 首屏会话透出的展示内容
   * @param string $text markdown格式的消息
   * @param array $btns 按钮，每个元素包含 title(按钮方案)、actionURL(点击按钮触发的URL)
   * @param int $btnOrientation 0-按钮竖直排列，1-按钮横向排列
   * @param int $hideAvatar 0-正常发消息者头像，1-隐藏发消息者头像
   * @return array
   */
  public function actionCard(string $title, string $text, array $btns = [], int $btnOrientation = 0, int $hideAvatar = 0): array
  {
    $data = [
      'msgtype' => 'actionCard',
      'actionCard' => [
        'title' => $title,
        'text' => $text,
        'btnOrientation' => $btnOrientation,
        'hideAvatar' => $hideAvatar,
      ]
    ];

    if (count($btns) === 1) {
      $btn = $btns[0];
      $data['actionCard']['singleTitle'] = $btn['title'];
      $data['actionCard']['singleURL'] = $btn['actionURL'];
    } else {
      $data['actionCard']['btns'] = $btns;
    }

    return $this->sendMsgNotice($data);
  }

  /**
   * 发送FeedCard
   * @param array $links 链接，每个元素包含 title(单条信息文本)、messageURL(点击单条信息到跳转链接)、picURL(单条信息后面图片的URL)
   * @return array
   */
  public function feedCard(array $links=[]): array
  {
    $data = [
      'msgtype' => 'feedCard',
      'feedCard' => [
        'links' => $links
      ]
    ];

    return $this->sendMsgNotice($data);
  }

  /**
   * 发送通知
   *
   * @param array $message
   * @return null|array
   */
  public function sendMsgNotice(array $message): ?array
  {
    $res = $this->sendNotice($this->webhookUrl, $message);
    if ($res['errcode'] != 0) {
      throw new NoticeHttpRequestException($res['errmsg'], $res['errcode']);
    }

    return $res;
  }
}
