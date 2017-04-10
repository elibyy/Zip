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
namespace Elibyy\Zip;

use Elibyy\Adapters\ZipAdapter;
use Elibyy\General\Adapter;
use Elibyy\General\File as GeneralFile;

/**
 * Class File
 * this class is a wrapper for the file in archive
 *
 * @version 1.0
 * @author  elibyy <elibyy@eyurl.com>
 * @package Elibyy\Zip
 */
class File implements
    GeneralFile
{

    /**
     * @var array the conversion of int types to string visual names
     * @since 1.0
     */
    protected static $comparisonTypes = array(
        \ZipArchive::CM_BZIP2 => 'bzip2',
        \ZipArchive::CM_DEFAULT => 'default',
        \ZipArchive::CM_DEFLATE => 'deflate',
        \ZipArchive::CM_DEFLATE64 => 'deflate64',
        \ZipArchive::CM_LZ77 => 'lz77',
        \ZipArchive::CM_LZMA => 'lzma',
        \ZipArchive::CM_IMPLODE => 'implode',
        \ZipArchive::CM_STORE => 'store',
        \ZipArchive::CM_PKWARE_IMPLODE => 'pkware implode',
        \ZipArchive::CM_PPMD => 'ppmd',
        \ZipArchive::CM_REDUCE_1 => 'reduce 1',
        \ZipArchive::CM_REDUCE_2 => 'reduce 2',
        \ZipArchive::CM_REDUCE_3 => 'reduce 3',
        \ZipArchive::CM_REDUCE_4 => 'reduce 4',
        \ZipArchive::CM_SHRINK => 'shrink',
        \ZipArchive::CM_TERSE => 'trese',
        \ZipArchive::CM_WAVPACK => 'wavpack',
    );

    /**
     * @var array the file data
     * @since 1.0
     */
    protected $data = array();

    /**
     * the adapter associated with the file
     *
     * @var Adapter
     * @since 1.0
     */
    protected $adapter;

    public function __construct($args, Adapter $adapter)
    {
        $this->data = $args;
        $this->adapter = $adapter;
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return $this->data['name'];
    }

    /**
     * @inheritdoc
     */
    public function getExtension()
    {
        return pathinfo($this->data['name'], PATHINFO_EXTENSION);
    }

    /**
     * @inheritdoc
     */
    public function getModifiedAt($format = null)
    {
        if ($format === null) {
            return $this->data['mtime'];
        } else {
            return date($format, $this->getModifiedAt());
        }
    }

    /**
     * @inheritdoc
     */
    public function getIndex()
    {
        return $this->data['index'];
    }

    /**
     * @inheritdoc
     */
    public function getSize()
    {
        return $this->data['size'];
    }

    /**
     * @inheritdoc
     */
    public function getCompressedSize()
    {
        return $this->data['comp_size'];
    }

    public function getCrc()
    {
        return $this->data['crc'];
    }

    /**
     * @inheritdoc
     */
    public function getCompressionMethod()
    {
        return $this->data['comp_method'];
    }

    /**
     * @inheritdoc
     */
    public function getStringCompressionMethod()
    {
        return self::$comparisonTypes[$this->getCompressionMethod()];
    }

    /**
     * @inheritdoc
     */
    public function setComment($comment)
    {
        return $this->getAdapter()->getArchive()->setCommentIndex($this->getIndex(), $comment);
    }

    /**
     * @inheritdoc
     */
    public function getComment()
    {
        return $this->getAdapter()->getArchive()->getCommentIndex($this->getIndex());
    }

    /**
     * @inheritdoc
     * @return ZipAdapter
     * @since 1.0
     */
    public function getAdapter()
    {
        return $this->adapter;
    }
}
