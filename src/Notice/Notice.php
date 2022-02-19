<?php declare(strict_types=1);


namespace Notice\Notice;

/**
 * Class Notice
 * @package Notice
 */
class Notice implements NoticeInterface
{
  protected $httpRequest = null;

  /**
   * 发送通知
   *
   * @param string $url
   * @param array $message
   * @param string $method
   * @return mixed
   */
  public function sendNotice(string $url, array $message, string $method = 'post')
  {
    if (is_null($this->httpRequest)) {
      $this->httpRequest = new HttpRequest();
    }

    return $this->httpRequest->$method($url, $message);
  }
}
