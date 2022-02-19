<?php declare(strict_types=1);

namespace Notice\Notice;

use Closure;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Handler\CurlHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;

/**
 * Class HttpRequest
 * @package Notice\Notice
 */
class HttpRequest
{
  private const RETRY_NUMBER   = 3;     //最大重试次数
  private const DELAY_TIME     = 10;    //延迟时间；默认[重试次数*1000]微秒
  private const TIMEOUT_TIME   = 3.14;  //超时时间
  private $requestHttp = null;

  public function __construct()
  {
    if (is_null($this->requestHttp)) {
      $stack = HandlerStack::create(new CurlHandler());
      $stack->push(Middleware::retry($this->retryDecider(), $this->retryDelay()));
      $this->requestHttp = new Client(['handler' => $stack]);
    }
  }

  /**
   * json格式的post请求
   *
   * @param string $url
   * @param array $params
   * @return mixed
   * @throws GuzzleException
   */
  public function post(string $url, array $params)
  {
    try {
      $response = $this->requestHttp->request('POST', $url, [
        'timeout' => self::TIMEOUT_TIME,
        'headers' => $this->getJsonHeader(),
        'json'    => $params,
      ]);

      return $this->parseResponseData($response->getBody()->getContents());
    } catch (Exception $exception) {
      throw new NoticeHttpRequestException($exception->getMessage(), $exception->getCode());
    }
  }

  /**
   * get方式请求
   *
   * @param string $url
   * @param array $params
   * @return mixed
   * @throws GuzzleException
   */
  public function get(string $url, array $params)
  {
    try {
      $response = $this->requestHttp->request('GET', $url, [
        'timeout' => self::TIMEOUT_TIME,
        'headers' => $this->getJsonHeader(),
        'query'   => $params
      ]);

      return $this->parseResponseData($response->getBody()->getContents());
    } catch (Exception $exception) {
      throw new NoticeHttpRequestException($exception->getMessage(), $exception->getCode());
    }
  }

  /**
   * 重试条件决策方法 true：表示重试；false：表示结束重试
   *
   * @return Closure()
   */
  protected function retryDecider(): Closure
  {
    return function ($retries, Request $request, Response $response = null, $exception = null) {
      if ($retries >= self::RETRY_NUMBER) {
        return false;
      }

      if ($exception instanceof ConnectException || $exception instanceof RequestException) {
        return true;
      }

      if ($response) {
        if ($response->getStatusCode() >= 500) {
          return true;
        }
      }

      return false;
    };
  }

  /**
   * 每次重试的间隔时间 usleep($microseconds)
   * @param $retries //重试次数，底层的处理方式是：usleep($retries * 1000)
   *
   * @return Closure
   */
  protected function retryDelay(): Closure
  {
    return function($retries) {
      if (self::DELAY_TIME > 0) {
        return $retries * self::DELAY_TIME;
      }

      return $retries;
    };
  }

  /**
   * 获取json格式headers头
   *
   * @return string[]
   */
  private function getJsonHeader(): array
  {
    return ['Content-Type' => 'application/json;charset=utf-8'];
  }

  /**
   * 解析响应数据
   *
   * @param $data
   * @return mixed
   */
  private function parseResponseData($data)
  {
    return json_decode($data, true);
  }
}
