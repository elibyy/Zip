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

/**
 * Class TestCase
 * general purpose test case for PHPUnit
 *
 * @version 1.0
 * @author  elibyy <elibyy@eyurl.com>
 * @package Elibyy\Zip\Tests
 */
class TestCase extends
    \PHPUnit_Framework_TestCase
{

    /**
     * @var string main path for testing
     * @since 1.0
     */
    protected $path;

    /**
     * creating needed directories
     *
     * @since 1.0
     */
    protected function setUp()
    {
        $this->path = BP . DS . 'archives' . DS;
    }

    /**
     * deletes a folder recursively
     *
     * @param string $path the folder to delete
     *
     * @since 1.0
     */
    protected static function _deleteFolder($path)
    {
        /**
         * @var \SplFileInfo $item
         */
        $dir = new \RecursiveDirectoryIterator($path);
        while ($dir->valid()) {
            $item = $dir->current();
            if (!in_array($item->getFilename(), array('.', '..'))) {
                $isFile = ($item->isFile());
                $isFile ? unlink($item->getRealPath()) : self::_deleteFolder($item->getRealPath());
            }
            $dir->next();
        }
        rmdir($path);
    }

    /**
     * this creates a random 10 files for the creator to manipulate
     *
     * @since 1.0
     */
    protected function _createFiles()
    {
        $filesPath = $this->path . 'files' . DS;
        if (!file_exists($filesPath)) {
            mkdir($filesPath, 0777, true);
        }
        for ($i = 0; $i < 10; $i++) {
            $fileName = $filesPath . $i . '.txt';
            file_put_contents($fileName, rand() . rand());
        }
    }

    protected function _deleteFiles()
    {
        $filesPath = $this->path . 'files' . DS;
        $this->_deleteFolder($filesPath);
        mkdir($filesPath);
    }
}
