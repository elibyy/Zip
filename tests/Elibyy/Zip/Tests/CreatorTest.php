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

/**
 * Class CreatorTest
 * testing creation of all supported types
 *
 * @version 1.0
 * @author  elibyy <elibyy@eyurl.com>
 * @package Elibyy\Zip\Tests
 */
class CreatorTest extends
    TestCase
{

    /**
     * setup the test
     *
     * @since 1.0
     */
    protected function setUp()
    {
        parent::setUp();
        if (!file_exists($this->path)) {
            mkdir($this->path);
            mkdir($this->path . DS . 'files');
        }
        $this->_createFiles();
    }

    /**
     * test create a ZIP file
     *
     * @since 1.0
     */
    public function testZipCreate()
    {
        $file = $this->path . 'test.zip';
        $filesPath = $this->path . 'files' . DS;
        $creator = new Creator($file);
        $dir = new \RecursiveDirectoryIterator($filesPath);
        while ($dir->valid()) {
            $item = $dir->current();
            if ($item->isFile()) {
                $add = $creator->addFile($item->getRealPath(), $item->getFilename());
                $this->assertTrue($add, sprintf('the file %s was not added to the tar file', $item->getFilename()));
            }
            $dir->next();
        }
        $this->assertFileExists($file);
        $this->assertEquals(10, $creator->getFilesCount());
    }

    /**
     * test create TAR file
     *
     * @since 1.0
     */
    public function testTarCreate()
    {
        /**
         * @var \SplFileInfo $item
         */
        $file = $this->path . 'test.tar';
        $filesPath = $this->path . 'files' . DS;
        $creator = new Creator($file);
        $dir = new \RecursiveDirectoryIterator($filesPath);
        while ($dir->valid()) {
            $item = $dir->current();
            if ($item->isFile()) {
                $add = $creator->addFile($item->getRealPath(), $item->getFilename());
                $this->assertTrue($add, sprintf('the file %s was not added to the tar file', $item->getFilename()));
            }
            $dir->next();
        }
        $this->assertFileExists($file);
        $this->assertEquals(10, $creator->getFilesCount());
    }

    /**
     * test create GZ file
     *
     * @since 1.0
     */
    public function testGzCreate()
    {
        /**
         * @var \SplFileInfo $item
         */
        $file = $this->path . 'test.gz';
        $filesPath = $this->path . 'files' . DS;
        $creator = new Creator($file);
        $dir = new \RecursiveDirectoryIterator($filesPath);
        while ($dir->valid()) {
            $item = $dir->current();
            if ($item->isFile()) {
                $add = $creator->addFile($item->getRealPath(), $item->getFilename());
                $this->assertTrue($add, sprintf('the file %s was not added to the tar file', $item->getFilename()));
            }
            $dir->next();
        }
        $this->assertFileExists($file);
        $this->assertEquals(10, $creator->getFilesCount());
    }

    /**
     * test create BZ2 file
     *
     * @since 1.0
     */
    public function testBz2Create()
    {
        /**
         * @var \SplFileInfo $item
         */
        $file = $this->path . 'test.bz2';
        $filesPath = $this->path . 'files' . DS;
        $creator = new Creator($file);
        $dir = new \RecursiveDirectoryIterator($filesPath);
        while ($dir->valid()) {
            $item = $dir->current();
            if ($item->isFile()) {
                $add = $creator->addFile($item->getRealPath(), $item->getFilename());
                $this->assertTrue($add, sprintf('the file %s was not added to the tar file', $item->getFilename()));
            }
            $dir->next();
        }
        $this->assertFileExists($file);
        $this->assertEquals(10, $creator->getFilesCount());
    }

    /**
     * test add a folder to the archive
     *
     * @since 1.0
     */
    public function testAddFolder()
    {
        $file = $this->path . 'test-folder.zip';
        $filesPath = $this->path . 'files' . DS;
        $creator = new Creator($file);
        $creator->addFolder($filesPath);
        $this->assertFileExists($file);
        $this->assertEquals(10, $creator->getFilesCount());
    }
}
 