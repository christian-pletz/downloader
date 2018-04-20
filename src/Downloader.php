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
 * Class Downloader
 *
 * @package ChristianPletz\Downloader
 */
class Downloader
{
    /**
     * @var DownloaderAbstract
     */
    private $downloader;

    /**
     * @return DownloaderAbstract
     */
    public function getDownloader(): DownloaderAbstract
    {
        if (null === $this->downloader) {
            $this->downloader = new Curl();
        }

        return $this->downloader;
    }
}