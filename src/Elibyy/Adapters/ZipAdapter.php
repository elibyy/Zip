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
use Elibyy\Zip\File as ZipFile;

/**
 * Class ZipAdapter
 * this class is an adapter for zip files
 *
 * @version 1.0
 * @author  elibyy <elibyy@eyurl.com>
 * @package Elibyy\Adapters
 */
class ZipAdapter implements
    Adapter
{

    /**
     * the supported file extensions
     *
     * @var array
     * @since 1.0
     */
    protected static $supported = array(
        'zip'
    );

    /**
     * @var \ZipArchive
     * @since 1.0
     */
    protected $archive;

    /**
     * @inheritdoc
     */
    public function __construct()
    {
        if (!class_exists('ZipArchive')) {
            throw new \Exception("ZipArchive class is required");
        }
        $this->archive = new \ZipArchive();
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
        $files = array();
        for ($i = 0; $i < $this->getFilesCount(); $i++) {
            $file = new ZipFile($this->getArchive()->statIndex($i), $this);
            if ($file->getIndex() !== null) {
                $files[$file->getIndex()] = $file;
            }
        }

        return $files;
    }

    /**
     * @inheritdoc
     */
    public function getFilesCount()
    {
        return $this->getArchive()->numFiles;
    }

    /**
     * @inheritdoc
     */
    public function getArchive()
    {
        return $this->archive;
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
        return $this->getArchive()->filename;
    }

    /**
     * @inheritdoc
     */
    public function getComment()
    {
        return $this->getArchive()->getArchiveComment();
    }

    /**
     * @inheritdoc
     */
    public function setComment($comment)
    {
        $this->getArchive()->setArchiveComment($comment);

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
    public function open($file, $flags = \ZipArchive::CREATE)
    {
        $this->getArchive()->open($file, $flags);
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
        $localName = ($localName === null) ? basename($path) : $localName;
        $length = ($localName === null) ? filesize($path) : $length;
        $results = $this->getArchive()->addFile($path, $localName, $start, $length);
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
        $results = $this->getArchive()->deleteIndex($file->getIndex());
        $this->updateArchive();

        return $results;
    }

    /**
     * @inheritdoc
     */
    public function removeFileByName($name)
    {
        $results = $this->getArchive()->deleteName($name);
        $this->updateArchive();

        return $results;
    }

    /**
     * @inheritdoc
     */
    public function addGlob($glob, $flags = GLOB_BRACE, $options = array())
    {
        $results = $this->getArchive()->addGlob($glob, $flags, $options);
        $this->updateArchive();

        return $results;
    }

    /**
     * @inheritdoc
     */
    public function addPattern($pattern, $directory, $options = array())
    {
        if (!array_key_exists('add_path', $options)) {
            $options['add_path'] = basename($directory) . DIRECTORY_SEPARATOR;
        }
        if (!array_key_exists('remove_path', $options)) {
            $options['remove_path'] = $directory;
        }
        $results = $this->getArchive()->addPattern($pattern, $directory, $options);
        $this->updateArchive();

        return $results;
    }

    /**
     * @inheritdoc
     */
    public function compress($format)
    {
        return $this->updateArchive();
    }
}
