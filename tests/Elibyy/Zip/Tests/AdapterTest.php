<?php
/**
 * This file is part of the Elibyy\Zip package.
 *
 * (c) Eli Y. <elibyy@eyurl.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * PHP version 5
 */
namespace Elibyy\Zip\Tests;

use Elibyy\Reader;

/**
 * Class AdapterTest
 * testing adapter features in the reader class
 *
 * @version 1.0
 * @author  elibyy <elibyy@eyurl.com>
 * @package Elibyy\Zip\Tests
 */
class AdapterTest extends
    TestCase
{

    /**
     * test setup
     *
     * @since 1.0
     */
    protected function setUp()
    {
        parent::setUp();
        $zipFile = $this->path . 'test.zip';
        $z7File = $this->path . 'notSupported.7z';
        if (!file_exists($this->path)) {
            mkdir($this->path);
        }
        touch($z7File);
        touch($zipFile);
    }

    /**
     * testing that the adapter is instance of {@link Elibyy\General\Adapter}
     *
     * @since 1.0
     */
    public function testAdapter()
    {
        $file = $this->path . DS . 'test.zip';
        $reader = new Reader($file);
        $this->assertInstanceOf('Elibyy\General\Adapter', $reader->getAdapter());
    }

    /**
     * test a file with no adapter available
     * that will throw an excception
     *
     * @since 1.0
     */
    public function testNoAdapter()
    {
        $file = $this->path . 'notSupported.7z';
        try{
            new Reader($file);
        } catch(\Exception $e){
            $this->assertEquals(sprintf("No Adapter found for %s", $file), $e->getMessage());
        }
    }

    /**
     * cleans tests files created
     *
     * @since 1.0
     */
    public static function  tearDownAfterClass()
    {
        self::_deleteFolder(BP . DS . 'archives' . DS);
    }
}
 