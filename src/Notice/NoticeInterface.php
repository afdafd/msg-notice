<?php declare(strict_types=1);


namespace Notice\Notice;

interface NoticeInterface
{
  public function sendNotice(string $url, array $message);
}
