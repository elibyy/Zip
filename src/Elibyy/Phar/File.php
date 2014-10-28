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
namespace Elibyy\Phar;

use Elibyy\General\Adapter;
use Elibyy\General\File as GeneralFile;

/**
 * Class File
 *
 * @version 1.0
 * @author  elibyy <elibyy@eyurl.com>
 * @package Elibyy\Phar
 */
class File implements
    GeneralFile
{

    protected $_file;

    protected $_adapter;

    protected static $comparisonTypes = array(
        'phar' => 'PHP Archive',
        'tar' => 'TAR',
        'tar.gz' => 'TAR GZIP',
        'tar.bz2' => 'TAR BZIP2',
        'bz2' => 'BZIP2',
        'gz' => 'GZIP',
    );

    /**
     * @param \PharFileInfo $file
     * @param Adapter       $adapter
     */
    public function __construct($file, Adapter $adapter)
    {
        $this->_file = $file;
        $this->_adapter = $adapter;
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return $this->_file->getFilename();
    }

    /**
     * @inheritdoc
     */
    public function getExtension()
    {
        return pathinfo($this->getName(), PATHINFO_EXTENSION);
    }

    /**
     * @inheritdoc
     */
    public function getModifiedAt($format = null)
    {
        if ($format === null) {
            return $this->_file->getMTime();
        }
        return date($format, $this->getModifiedAt());
    }

    /**
     * @inheritdoc
     */
    public function getIndex()
    {
        return false;
    }

    /**
     * @inheritdoc
     */
    public function getSize()
    {
        return $this->_file->getSize();
    }

    /**
     * @inheritdoc
     */
    public function getCompressedSize()
    {
        return $this->_file->getCompressedSize();
    }

    /**
     * @inheritdoc
     */
    public function getCrc()
    {
        return $this->_file->getCRC32();
    }

    /**
     * @inheritdoc
     */
    public function getCompressionMethod()
    {
        return false;
    }

    /**
     * @inheritdoc
     */
    public function getStringCompressionMethod()
    {
        $ext = pathinfo($this->getAdapter()->getFilename(), PATHINFO_EXTENSION);
        return self::$comparisonTypes[$ext];
    }

    /**
     * @inheritdoc
     */
    public function setComment($comment)
    {
        return false;
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
    public function getAdapter()
    {
        return $this->_adapter;
    }
}
