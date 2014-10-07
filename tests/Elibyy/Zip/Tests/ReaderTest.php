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
 * Class ReadingTest
 * testing reader among all supported files and not supported files
 *
 * @version 1.0
 * @author  elibyy <elibyy@eyurl.com>
 * @package Elibyy\Zip\Tests
 */
class ReadingTest extends
    TestCase
{

    /**
     * deletes created test files
     *
     * @since 1.0
     */
    public static function tearDownAfterClass()
    {
        self::_deleteFolder(BP . DS . 'archives' . DS);
    }

    /**
     * testing ZIP reading
     *
     * @since 1.0
     */
    public function testReaderZip()
    {
        $file = BP . DS . 'archives' . DS . 'test.zip';
        $reader = new Reader($file);
        $this->assertEquals(10, $reader->getFilesCount(), 'Files Count test');
        $this->assertEquals($file, $reader->getFilename());
    }

    /**
     * testing TAR reading
     *
     * @since 1.0
     */
    public function testReaderTar()
    {
        $file = BP . '/archives/test.tar';
        $reader = new Reader($file);
        $files = $reader->getFiles();
        $this->assertTrue(!empty($files));
        $this->assertEquals(10, $reader->getFilesCount(), 'Files Count test');
        $this->assertEquals($file, $reader->getFilename());
    }

    /**
     * testing GZ reading
     *
     * @since 1.0
     */
    public function testReaderGz()
    {
        $file = BP . DS . 'archives' . DS . 'test.gz';
        $reader = new Reader($file);
        $this->assertEquals(10, $reader->getFilesCount(), 'Files Count test');
        $this->assertEquals($file, $reader->getFilename());
    }

    /**
     * testing bz2 reading
     *
     * @since 1.0
     */
    public function testReaderBz2()
    {
        $file = BP . DS . 'archives' . DS . 'test.bz2';
        $reader = new Reader($file);
        $this->assertEquals(10, $reader->getFilesCount(), 'Files Count test');
        $this->assertEquals($file, $reader->getFilename());
    }

    /**
     * testing not existent file
     *
     * @since 1.0
     */
    public function testFileNotExist()
    {
        $file = BP . DS . 'archives' . DS . 'null.zip';
        try{
            new Reader($file);
        } catch(\Exception $e){
            $this->assertEquals(sprintf("file %s doesn't exist", $file), $e->getMessage());
        }
    }

    /**
     * tests unzip an archive to a specific destination
     *
     * @since 1.0
     */
    public function testUnzip()
    {
        $reader = new Reader($this->path . 'test.zip');
        $destination = $this->path . 'out';
        $reader->unzip($destination);
        $total = count(scandir($destination));
        $this->assertEquals(12, $total);
    }

    /**
     * tests unzip an archive with no destination specified
     *
     * @since 1.0
     */
    public function testUnzipNoDestination()
    {
        $reader = new Reader($this->path . 'test.zip');
        $reader->unzip();
        $files = array();
        foreach (scandir($this->path) as $file) {
            $ext = pathinfo($file, PATHINFO_EXTENSION);
            if ($ext == 'txt') {
                $files[] = $file;
            }
        }
        $this->assertEquals(10, count($files));
    }

    /**
     * testing getArchive not returning null
     *
     * @since 1.0
     */
    public function testArchive()
    {
        $reader = new Reader($this->path . 'test.zip');
        $this->assertNotNull($reader->getArchive());
    }

    /**
     * testing comment getter/setter for the archive
     *
     * @since 1.0
     */
    public function testComment()
    {
        $reader = new Reader($this->path . 'test.zip');
        $this->assertNotEquals(false, $reader->setComment('test'));
        $this->assertEquals('test', $reader->getComment());
    }

    /**
     * testing archive updating
     *
     * @since 1.0
     */
    public function testUpdate()
    {
        $reader = new Reader($this->path . 'test.zip');
        $this->assertNotNull($reader->updateArchive());
    }

    /**
     * testing compressaion changing
     *
     * @since 1.0
     */
    public function testCompress()
    {
        $reader = new Reader($this->path . 'test.bz2');
        $results = $reader->compress(4096);
        $this->assertInstanceOf('PharData', $results);
    }

    /**
     * testing removal of file from the archive
     *
     * @since 1.0
     */
    public function testRemoveFile()
    {
        $reader = new Reader($this->path . 'test.zip');
        $files = $reader->getFiles();
        $file1 = $files[0];
        $file2 = $files[1];
        $res1 = $reader->removeFileByObject($file1);
        $res2 = $reader->removeFileByName($file2->getName());
        $this->assertTrue($res1);
        $this->assertTrue($res2);
    }

    /**
     * testing addition by patterns to the archive
     *
     * @since 1.0
     */
    public function testPatterns()
    {
        $reader = new Reader($this->path . 'test.zip');
        $filesPath = $this->path . 'files';
        chdir($filesPath);
        $globRes = $reader->addGlob('*.txt');
        $patternRes = $reader->addPattern('/\.(?:txt)$/', $filesPath);
        $this->assertTrue(!empty($globRes));
        $this->assertTrue(!empty($patternRes));
    }
}
 