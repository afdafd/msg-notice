<?php declare(strict_types=1);


namespace Notice\Notice;

/**
 * Class Notice
 * @package Notice
 *
 * @method static \Notice\Notice\DinDin\DinDinNotice dinDin($config)
 * @method static \Notice\Notice\Email\EmailNotice email($config)
 * @method static \Notice\Notice\Sms\SmsNotice sms($config)
 * @method static \Notice\Notice\Wechat\WechatNotice wechat($config)
 */
class NoticeFactory
{
  /**
   * 动态的处理应用程序
   *
   * @param string $name
   * @param array $arguments
   *
   * @return object
   */
  public static function __callStatic(string $name, array $arguments)
  {
    $namespace = self::getFullNamespace($name);
    $namespacePath = "\\Notice\\Notice\\$namespace\\{$namespace}Notice";
    return new $namespacePath(...$arguments);
  }

  /**
   * 命名空间地址处理
   *
   * @param $value
   * @return array|string|string[]
   */
  private static function getFullNamespace($value)
  {
    $value = ucwords(str_replace(['-', '_'], ' ', $value));
    return str_replace(' ', '', $value);
  }
}
