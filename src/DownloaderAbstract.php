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
 * Class DownloaderAbstract
 *
 * @package ChristianPletz\Downloader
 */
abstract class DownloaderAbstract
{

    /**
     * Downloads data from given url
     *
     * @param string $url
     *
     * @return string
     */
    abstract public function download(string $url): string;
}