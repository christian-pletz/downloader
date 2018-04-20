<?php
/**
 * @author      Christian Pletz <info@christian-pletz.de>
 * @copyright   Copyright (c) 2018 Christian Pletz
 */

/**
 * namespace definition and usage
 */

namespace ChristianPletzTest\Downloader;

use ChristianPletz\Downloader\Curl;
use PHPUnit\Framework\TestCase;

/**
 * Class CurlTest
 *
 * @package ChristianPletzTest\Downloader
 */
class CurlTest extends TestCase
{
    /**
     * @var Curl
     */
    private $handle;

    protected function setUp()
    {
        $this->handle = new Curl();
    }


    protected function tearDown()
    {
        unset($this->handle);
    }

    public function testSetSleep()
    {
        $min = 3;
        $max = 5;

        $this->handle->setSleep($min, $max);

        $this->assertSame($min, $this->handle->getSleepMin());
        $this->assertSame($max, $this->handle->getSleepMax());
    }

    public function testAddPostData()
    {
        $this->handle->addPostData('foo', 'baz');

        $this->assertSame(['foo' => 'baz'], $this->handle->getPostData());
    }

    public function testIsSslHostCheckEnabledl()
    {
        $this->assertTrue($this->handle->isSslHostCheckEnabled());

        $this->handle->disableSslHostCheck();

        $this->assertFalse($this->handle->isSslHostCheckEnabled());

        $this->handle->enableSslHostCheck();

        $this->assertTrue($this->handle->isSslHostCheckEnabled());
    }

    public function testSetHttpAuth()
    {
        $this->assertNull($this->handle->getHttpAuth());

        $this->handle->setHttpAuth('username', 'password');

        $this->assertSame(
            ['username' => 'username', 'password' => 'password'],
            $this->handle->getHttpAuth()
        );
    }

    public function testAddHeaders()
    {
        $this->assertEmpty($this->handle->getHeaders());

        $this->handle->addHeader('headerkey', 'headerValue');

        $this->assertSame(
            ['headerkey: headerValue'], $this->handle->getHeaders()
        );
    }
}