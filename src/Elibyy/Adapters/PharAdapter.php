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
namespace Elibyy\Adapters;

use Elibyy\General\Adapter;
use Elibyy\General\File;
use Elibyy\Phar\File as PharFile;
use Phar;
use PharFileInfo;

/**
 * Class PharAdapter
 * * this class is an adapter for phar,tar,tar.gz,bz2,gz files
 *
 * @version 1.0
 * @author  elibyy <elibyy@eyurl.com>
 * @package Elibyy\Adapters
 */
class PharAdapter implements
    Adapter
{

    /**
     * the supported file extensions
     *
     * @var array
     * @since 1.0
     */
    protected static $supported = array(
        'phar',
        'tar',
        'tar.gz',
        'tar.bz2',
        'bz2',
        'gz'
    );

    protected $_file;

    /**
     * @var \PharData
     * @since 1.0
     */
    protected $_archive;

    /**
     * @inheritdoc
     */
    public function __construct()
    {
        if (!class_exists('PharData')) {
            throw new \Exception("PharData class is required");
        }
    }

    /**
     * @inheritdoc
     */
    public static function supports($type)
    {
        return in_array($type, self::$supported);
    }

    /**
     * @inheritdoc
     */
    public function getFiles()
    {
        /**
         * @var PharFileInfo $item
         */
        $files = array();
        $this->getArchive()->rewind();
        while ($this->getArchive()->valid()) {
            $files[] = new PharFile($this->getArchive()->current(), $this);
            $this->getArchive()->next();
        }
        $this->getArchive()->rewind();

        return $files;
    }

    /**
     * @inheritdoc
     */
    public function getArchive()
    {
        return $this->_archive;
    }

    /**
     * @inheritdoc
     */
    public function unzip($destination = null, $entries = null)
    {
        if ($destination === null) {
            $destination = dirname($this->getFilename());
            (!file_exists($destination)) ? mkdir($destination) : null;
        }

        return $this->getArchive()->extractTo($destination, $entries);
    }

    /**
     * @inheritdoc
     */
    public function getFilename()
    {
        return $this->_file;
    }

    /**
     * @inheritdoc
     */
    public function getFilesCount()
    {
        return $this->getArchive()->count();
    }

    /**
     * @inheritdoc
     */
    public function getComment()
    {
        return false;
    }

    /**
     * @inheritdoc
     */
    public function setComment($comment)
    {
        return $this->updateArchive();
    }

    /**
     * @inheritdoc
     */
    public function updateArchive()
    {
        $this->open($this->getFilename());

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function open($file, $flags = Phar::KEY_AS_FILENAME)
    {
        $this->_file = $file;
        $this->_archive = new \PharData($file, $flags);
    }

    /**
     * @inheritdoc
     */
    public function addFolder($path, $parent = '')
    {
        /**
         * @var \SplFileInfo $item
         */
        if (file_exists($path)) {
            $parent = ($parent === null) ? '' : $parent . DIRECTORY_SEPARATOR;
            $innerPath = $parent . basename($path);
            $dir = new \RecursiveDirectoryIterator($path);
            $dir->rewind();
            while ($dir->valid()) {
                $item = $dir->current();
                if ($item->isDir()) {
                    if (!in_array($item->getFilename(), array('.', '..'))) {
                        $this->addFolder($item->getRealPath(), $innerPath);
                    }
                } else {
                    $this->addFile($item->getRealPath(), $innerPath . DIRECTORY_SEPARATOR . $item->getFilename(), true);
                }
                $dir->next();
            }
        }

        return $this->updateArchive();
    }

    /**
     * @inheritdoc
     */
    public function addFile($path, $localName = null, $start = 0, $length = null, $noUpdate = false)
    {
        $results = true;
        $localName = ($localName === null) ? basename($path) : $localName;
        try {
            $this->getArchive()->addFile($path, $localName);
        } catch (\Exception $e) {
            $results = false;
        }
        if (!$noUpdate) {
            $this->updateArchive();
        }

        return $results;
    }

    /**
     * @inheritdoc
     */
    public function removeFileByObject(File $file)
    {
        return $this->removeFileByName($file->getName());
    }

    /**
     * @inheritdoc
     */
    public function removeFileByName($name)
    {
        $results = $this->getArchive()->delete($name);
        $this->updateArchive();

        return $results;
    }

    /**
     * @inheritdoc
     */
    public function addGlob($glob, $flags = GLOB_BRACE, $options = array())
    {
        return false;
    }

    /**
     * @inheritdoc
     */
    public function addPattern($pattern, $directory, $options = array())
    {
        return false;
    }

    /**
     * @inheritdoc
     */
    public function compress($format = \PharData::GZ)
    {
        return $this->getArchive()->compress($format);
    }
}
