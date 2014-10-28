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

use Elibyy\Creator;
use Elibyy\Reader;
use Symfony\Component\Config\Definition\Exception\Exception;

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
        $this->assertInstanceOf('Elibyy\Adapters\ZipAdapter', $reader->compress(null));
    }

    /**
     * test a file with no adapter available
     * that will throw an exception
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

    public function testNoAdapters()
    {
        $ref = new \ReflectionClass('Elibyy\Adapters\TestAdapter');
        $adaptersFolder = dirname($ref->getFileName());
        $newName = str_replace('Adapters', 'Test', $adaptersFolder);
        rename($adaptersFolder, $newName);
        $file = $this->path . 'test.zip';
        try{
            $reader = new Reader($file);
        } catch(\Exception $e){
            $msg = sprintf('the adapters folder is missing should be in the path %s', $adaptersFolder);
            $this->assertEquals($msg,
                $e->getMessage());
        }
        rename($newName, $adaptersFolder);
    }

    public function testRecursiveDirZip()
    {
        $file = $this->path . 'testRecursive.zip';
        $creator = new Creator($file);
        $adapter = $creator->addFolder(BP . DS . 'src');
        $this->assertInstanceOf('Elibyy\General\Adapter', $adapter);
        $this->assertEquals(9, $creator->getFilesCount());
    }

    public function testRecursiveDirTar()
    {
        $file = $this->path . 'testRecursive.tar';
        $creator = new Creator($file);
        $adapter = $creator->addFolder(BP . DS . 'src');
        $this->assertInstanceOf('Elibyy\General\Adapter', $adapter);
        $this->assertEquals(9, $creator->getFilesCount());
    }

    public function testPharCreate()
    {
        $file = $this->path . 'test.tar';
        $this->_createFiles();
        $creator = new Creator($file);
        $creator->addFolder($this->path . 'files');
    }

    public function testUnzipPhar()
    {
        $this->_deleteFiles();
        $file = $this->path . 'test.tar';
        $reader = new Reader($file);
        $reader->unzip();
        $reader->unzip($this->path . 'test');
    }

    /**
     * cleans tests files created
     *
     * @since 1.0
     */
    public static function tearDownAfterClass()
    {
        self::_deleteFolder(BP . DS . 'archives' . DS);
    }
}
