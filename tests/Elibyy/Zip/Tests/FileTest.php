<?php
/**
 * Class FileTest
 *
 * @version 1.0
 * @package Elibyy\Zip\Tests
 */
namespace Elibyy\Zip\Tests;

use Elibyy\Reader;

class FileTests extends
    TestCase
{

    public function testAllMethodsZip()
    {
        $zipFile = $this->path . 'test.zip';
        $reader = new Reader($zipFile);
        $files = $reader->getFiles();
        foreach ($files as $file) {
            $this->assertInternalType('int', $file->getIndex());
            $this->assertInternalType('string', $file->getName());
            $this->assertEquals('txt', $file->getExtension());
            $this->assertInternalType('int', $file->getModifiedAt());
            $this->assertInternalType('string', $file->getModifiedAt('d/m/y'));
            $this->assertInstanceOf('Elibyy\Adapters\ZipAdapter', $file->getAdapter());
            $this->assertInternalType('int', $file->getSize());
            $this->assertInternalType('int', $file->getCompressedSize());
            $file->setComment('test');
            $this->assertEquals('test', $file->getComment());
            $this->assertInternalType('numeric', $file->getCrc());
            $this->assertInternalType('int', $file->getCompressionMethod());
            $this->assertInternalType('string', $file->getStringCompressionMethod());
        }
    }

    public function testAllMethodsPhar()
    {
        $zipFile = $this->path . 'test.bz2';
        $reader = new Reader($zipFile);
        $files = $reader->getFiles();
        foreach ($files as $file) {
            $this->assertEquals(false, $file->getIndex());
            $this->assertInternalType('string', $file->getName());
            $this->assertEquals('txt', $file->getExtension());
            $this->assertInternalType('int', $file->getModifiedAt());
            $this->assertInternalType('string', $file->getModifiedAt('d/m/y'));
            $this->assertInstanceOf('Elibyy\Adapters\PharAdapter', $file->getAdapter());
            $this->assertInternalType('int', $file->getSize());
            $this->assertInternalType('int', $file->getCompressedSize());
            $file->setComment('test');
            $this->assertEquals(false, $file->getComment());
            $this->assertInternalType('numeric', $file->getCrc());
            $this->assertEquals(false, $file->getCompressionMethod());
            $this->assertInternalType('string', $file->getStringCompressionMethod());
        }
    }
} 