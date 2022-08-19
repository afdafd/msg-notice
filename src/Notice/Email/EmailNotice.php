<?php declare(strict_types=1);

namespace Notice\Notice\Email;

use Notice\Notice\Notice;

/**
 * 邮箱通知
 *
 * Class EmailNotice
 * @package Notice\Email
 */
class EmailNotice extends Notice
{
    protected $baseConfig = [];

    public function __construct(array $config = [])
    {
        if (empty($config)) {
            throw new \Exception('配置信息不能为空');
        }

       $this->baseConfig = $config;
    }

    /**
     * 发送附件邮箱
     *
     * @param array $receive 接收者
     * @param string $resourcePath 资源路径
     * @param string $subject  描述
     * @param array $send 发送者
     * @param string $bodyContent 主体内容
     * @param bool $isCc 是否回传
     * @param array $ccPath 回传地址
     * @return int
     */
    public function sendAttach(
        array $receive,
        string $resourcePath,
        string $subject,
        array $send = [],
        string $bodyContent = '',
        bool $isCc = false,
        array $ccPath = []
    ): int
    {
        $fromAddress = $send['from_address'] ?? $this->baseConfig['from_address'];
        $fromName = $send['from_name'] ?? $this->baseConfig['from_name'];

        $message = (new \Swift_Message($subject, $bodyContent, null, 'UTF-8'))
            ->setFrom([$fromAddress => $fromName])
            ->setTo($receive)
            ->attach(\Swift_Attachment::fromPath($resourcePath));

        if ($isCc) {
            if (!empty($ccPath)) {
                $path = $ccPath['path'] ?? '';
                $name = $ccPath['name'] ?? null;
                $message->setCc($path, $name);
            }
        }

        return $this->getMailer()->send($message);
    }

    /**
     * 发送文本
     * @param array $receive
     * @param string $subject
     * @param string $bodyContent
     * @param array $send
     * @param bool $isCc
     * @param array $ccPath
     * @return int
     */
    public function sendText(
        array $receive,
        string $subject,
        string $bodyContent,
        array $send = [],
        bool $isCc = false,
        array $ccPath = []
    ): int
    {
        $fromAddress = $send['from_address'] ?? $this->baseConfig['from_address'];
        $fromName = $send['from_name'] ?? $this->baseConfig['from_name'];

        $message = (new \Swift_Message($subject))
            ->setFrom([$fromAddress => $fromName])
            ->setTo($receive)
            ->setBody($bodyContent)
            ->setCharset('UTF-8');

        if ($isCc) {
            if (!empty($ccPath)) {
                $path = $ccPath['path'] ?? '';
                $name = $ccPath['name'] ?? null;
                $message->setCc($path, $name);
            }
        }

        return $this->getMailer()->send($message);
    }

    /**
     * @return \Swift_Mailer
     */
    private function getMailer(): \Swift_Mailer
    {
        $transport = (new \Swift_SmtpTransport(
            $this->baseConfig['host'],
            $this->baseConfig['port'],
            $this->baseConfig['encryption']
        ))
        ->setUsername($this->baseConfig['username'])
        ->setPassword($this->baseConfig['password']);

       return new \Swift_Mailer($transport);
    }
}
