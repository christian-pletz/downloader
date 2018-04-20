<?php
/**
 * @author      Christian Pletz <info@christian-pletz.de>
 * @copyright   Copyright (c) 2018 Christian Pletz
 */

/**
 * namespace definition and usage
 */

namespace ChristianPletz\Downloader;

/**
 * Class Curl
 *
 * @package ChristianPletz\Downloader
 */
class Curl extends DownloaderAbstract
{
    /**
     * Sleep min in microseconds
     *
     * @var int
     */
    private $sleepMin = 0;

    /**
     * Sleep max in microseconds
     *
     * @var int
     */
    private $sleepMax = 0;

    /**
     * Sleep in while loop between retries in seconds
     *
     * @var int
     */
    private $sleepRetry = 1;

    /**
     * Transfer timeout in seconds
     *
     * @var int
     */
    private $timeout = 5;

    /**
     * Post data
     *
     * @var array
     */
    private $postData = [];

    /**
     * Curl resource
     *
     * @var \resource
     */
    private $curl;

    /**
     * Is ssl host check enabled?
     *
     * @var bool
     */
    private $sslHostCheckEnabled = true;

    /**
     * Htpasswd access data
     *
     * @var null|array htpasswd access data
     */
    private $httpAuth = null;

    /**
     * Max retries
     *
     * @var int
     */
    private $retriesMax = 1;

    /**
     * Headers for curl call
     *
     * @var array
     */
    private $headers = [];

    /**
     * Set sleep times
     *
     * @param int $min Sleep min in microseconds
     * @param int $max Sleep max in microseconds
     */
    public function setSleep(int $min, int $max)
    {
        $this->setSleepMin($min);
        $this->setSleepMax($max);
    }

    /**
     * Get sleep min
     *
     * @return int Sleep min in microseconds
     */
    public function getSleepMin()
    {
        return $this->sleepMin;
    }

    /**
     * Set sleep min
     *
     * @param int $sleepMin Sleep min in microseconds
     */
    public function setSleepMin(int $sleepMin)
    {
        $this->sleepMin = $sleepMin;
    }

    /**
     * Get sleep max
     *
     * @return int Sleep max in microseconds
     */
    public function getSleepMax(): int
    {
        return $this->sleepMax;
    }

    /**
     * Set sleep max
     *
     * @param int $sleepMax Sleep max in microseconds
     */
    public function setSleepMax(int $sleepMax)
    {
        $this->sleepMax = $sleepMax;
    }

    /**
     * Get sleep in while loop between retries in seconds
     *
     * @return int
     */
    public function getSleepRetry(): int
    {
        return $this->sleepRetry;
    }

    /**
     * Set sleep in while loop between retries in seconds
     *
     * @param int $sleepRetry
     */
    public function setSleepRetry(int $sleepRetry)
    {
        $this->sleepRetry = $sleepRetry;
    }

    /**
     * Get timeout
     *
     * @return int Timeout in seconds
     */
    public function getTimeout(): int
    {
        return $this->timeout;
    }

    /**
     * Set timeout
     *
     * @param int $timeout Timeout in seconds
     */
    public function setTimeout(int $timeout)
    {
        $this->timeout = $timeout;
    }

    /**
     * Get post data
     *
     * @return array
     */
    public function getPostData(): array
    {
        return $this->postData;
    }

    /**
     * Set post data
     *
     * @param array $postData
     */
    public function setPostData(array $postData)
    {
        $this->postData = $postData;
    }

    /**
     * Add post data to curl call
     *
     * @param string $key
     * @param string $value
     */
    public function addPostData(string $key, string $value)
    {
        $this->postData[$key] = $value;
    }

    /**
     * Get curl resource
     *
     * @return \resource
     */
    private function getCurl()
    {
        if (null === $this->curl || false === is_resource($this->curl)) {
            $this->curl = curl_init();
        }

        return $this->curl;
    }

    /**
     * Is ssl host check enabled?
     *
     * @return boolean
     */
    public function isSslHostCheckEnabled(): bool
    {
        return $this->sslHostCheckEnabled;
    }

    /**
     * Disable ssl host check
     */
    public function disableSslHostCheck()
    {
        $this->sslHostCheckEnabled = false;
    }

    /**
     * Enable ssl host check
     */
    public function enableSslHostCheck()
    {
        $this->sslHostCheckEnabled = true;
    }

    /**
     * @return array|null
     */
    public function getHttpAuth()
    {
        return $this->httpAuth;
    }

    /**
     * Set http auth for curl call
     *
     * @param string $userName htpasswd username
     * @param string $password htpasswd password
     */
    public function setHttpAuth(string $userName, string $password)
    {
        $this->httpAuth = array(
            'username' => $userName,
            'password' => $password,
        );
    }

    /**
     * Get max retries
     *
     * @return int
     */
    public function getRetriesMax(): int
    {
        return $this->retriesMax;
    }

    /**
     * Set max retries
     *
     * @param int $retriesMax
     */
    public function setRetriesMax(int $retriesMax)
    {
        $this->retriesMax = $retriesMax;
    }

    /**
     * @return array
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * @param array $headers
     */
    public function setHeaders(array $headers)
    {
        $this->headers = $headers;
    }

    /**
     * Adds header for curl call
     *
     * @param string $headerKey
     * @param string $headerValue
     */
    public function addHeader(string $headerKey, string $headerValue)
    {
        $this->headers[] = $headerKey . ': ' . $headerValue;
    }

    /**
     * Downloads data from given url
     *
     * @param string $url
     *
     * @return string
     */
    public function download(string $url): string
    {
        if ($this->getSleepMin()) {
            usleep(mt_rand($this->getSleepMin(), $this->getSleepMax()));
        }

        $curl = $this->getCurl();
        $url  = (string)$url;

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        if ($this->isSslHostCheckEnabled()) {
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 1);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
        }

        if ($this->getHttpAuth()) {
            curl_setopt(
                $curl, CURLOPT_USERPWD,
                $this->getHttpAuth()['username'] . ':' . $this->getHttpAuth(
                )['password']
            );
            curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        }

        if (count($this->getPostData()) > 0) {
            curl_setopt($curl, CURLOPT_POST, count($this->getPostData()));
            curl_setopt($curl, CURLOPT_POSTFIELDS, $this->getPostData());
        }

        if (count($this->getHeaders()) > 0) {
            curl_setopt($curl, CURLOPT_HTTPHEADER, $this->getHeaders());
        }

        $retries      = 0;
        $responseCode = null;

        while ($retries < $this->getRetriesMax()) {
            $response     = @curl_exec($curl);
            $responseCode = curl_getinfo($curl, CURLINFO_RESPONSE_CODE);

            if ($responseCode == CURLE_OK) {
                break;
            }

            $retries++;
            sleep(1);
        }

        if ($response === false || $responseCode != 200) {
            $curlError = curl_error($curl);
            throw new NetworkException($curlError);
        }

        return $response;

    }
}