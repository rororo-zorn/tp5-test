<?php

namespace app\common\tool\pipe;

abstract class Pipe
{
    const HTTP_GET = 'get';
    const HTTP_POST = 'post';
    const CONTENT_TYPE_FORM = 'form-data';
    const CONTENT_TYPE_URLENCODED = 'x-www-urlencoded';
    const CONTENT_TYPE_JSON = 'json';

    /**
     * 请求url
     * @var
     */
    protected $requestUrl;

    /**
     * 请求类型，默认为post
     * @var
     */
    protected $requestType = self::HTTP_POST;

    /**
     * content type
     * @var
     */
    protected $contentType = self::CONTENT_TYPE_JSON;

    /**
     * 超时时间
     * @var int
     */
    protected $timeout = 5;

    /**
     * 通信结果
     * @var
     */
    protected $result;

    /**
     * http请求
     * @param array $param
     * @return $this
     */
    protected function requestServerByHTTP($param = [])
    {
        $this->result = $this->sendRequestByCURL($param);
        return $this;
    }

    /**
     * cURL
     * @param array $param
     * @return bool|string
     */
    protected function sendRequestByCURL($param = [])
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_TIMEOUT, $this->timeout);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        if ($this->requestType == self::HTTP_POST) {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_URL, $this->requestUrl);

            switch ($this->contentType) {
                case self::CONTENT_TYPE_JSON:
                    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
                    $param = json_encode($param);
                    break;
                case self::CONTENT_TYPE_URLENCODED:
                    $param = http_build_query($param);
                    break;
                default:
                    break;
            }
            curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
        } else {
            $param = http_build_query($param);
            $url = $this->requestUrl . '?' . $param;
            curl_setopt($ch, CURLOPT_URL, $url);
        }

        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

    /**
     * 获取通信结果
     * @return mixed
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * 判断是否通信成功
     * @return bool
     */
    public function isSuccessOld()
    {
        return $this->result !== false;
    }

    /**
     * 判断是否通信成功
     * @return bool
     */
    public function isSuccess()
    {
        if ($this->result !== false) {
            $result = json_decode($this->result, true);
            if (isset($result['code']) && $result['code'] == 200) {
                return true;
            }
        }

        return false;
    }
}