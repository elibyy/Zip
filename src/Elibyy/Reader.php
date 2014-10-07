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
namespace Elibyy;

use Elibyy\General\Adapter;
use Elibyy\General\File;
use RuntimeException;

/**
 * Class Elibyy\Reader <br>
 * this class is abstract layer for the adapters for files
 *
 * @version 1.0
 * @package Elibyy
 * @author  elibyy <elibyy@eyurl.com>
 */
class Reader
{

    /**
     * this contains the adapter for the file specified
     *
     * @var Adapter
     * @since 1.0
     */
    protected $adapter;

    /**
     * initiate a new instance of the reader with the file specified
     *
     * @param string $file the file to load with the reader
     *
     * @throws \RuntimeException if no adapter found
     */
    public function __construct($file)
    {
        if (!file_exists($file)) {
            throw new RuntimeException(sprintf("file %s doesn't exist", $file));
        }
        $this->adapter = $this->_getAdapter($file);
        $this->getAdapter()->open($file);
    }

    /**
     * returns a new instance of the supported adapter for the specified file
     * <p style="font-weight: bold;">i haven't implemented the adapters to be extended outside of the namespace</p>
     * <br>
     * <p style="font-weight: bold;">this throws an exception if no adapter found that supports the file</p>
     *
     * @param string $file the file to get the adapter for
     *
     * @throws \RuntimeException  if no adapter found or adapter folder doesn't exist
     * @since 1.0
     * @return Adapter
     */
    protected function _getAdapter($file)
    {
        /**
         * @var \SplFileInfo $item
         */
        $adaptersDir = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'Adapters';
        if (!file_exists($adaptersDir)) {
            $msg = "the adapters folder is missing should be in the path %s";
            throw new \RuntimeException(sprintf($msg, $adaptersDir));
        }
        $dir = new \RecursiveDirectoryIterator($adaptersDir);
        $supports = array();
        $removes = array();
        while ($dir->valid()) {
            $item = $dir->current();
            if (pathinfo($item->getFilename(), PATHINFO_EXTENSION) == 'php') {
                $parentPath = dirname(dirname(__FILE__));
                $class = str_replace($parentPath, '', $item->getRealPath());
                $class = str_replace(DIRECTORY_SEPARATOR, '\\', $class);
                $class = str_replace('.php', '', $class);
                $ref = new \ReflectionClass($class);
                if ($ref->isInstantiable()) {
                    if ($class::supports(pathinfo($file, PATHINFO_EXTENSION))) {
                        $supports[$ref->getName()] = $ref;
                        if ($ref->getParentClass() != null) {
                            $removes[] = $ref->getParentClass()->getName();
                        }
                    }
                }
            }
            $dir->next();
        }
        foreach ($removes as $class) {
            unset($supports[$class]);
        }
        if (count($supports) == 1) {
            $class = array_shift($supports);
            $class = $class->getName();
            return new $class;
        }
        throw new \RuntimeException(sprintf("No Adapter found for %s", $file));
    }

    /**
     * returns the current adapter instance provided by {@link Reader#_getAdapter(string $file)}
     *
     * @return Adapter the current adapter instance
     * @since 1.0
     */
    public function getAdapter()
    {
        return $this->adapter;
    }

    /**
     *
     * @param string       $destination the destination folder to unzip to
     * @param string|array $entries     the specific entries to extract from the archive can be either <br>
     *                                  a specific file (string) or an array for files
     *
     * @return mixed
     * @since 1.0
     */
    public function unzip($destination = null, $entries = null)
    {
        return $this->getAdapter()->unzip($destination, $entries);
    }

    /**
     * this function returns the loaded archive name from the adapter
     *
     * @return string the archive filename
     * @since 1.0
     */
    public function getFilename()
    {
        return $this->getAdapter()->getFilename();
    }

    /**
     * returns the archive object from the adapter
     *
     * @return mixed the archive received from the adapter
     * @since 1.0
     */
    public function getArchive()
    {
        return $this->getAdapter()->getArchive();
    }

    /**
     * returns an array of {@link File} object to iterate on
     *
     * @return File[] the array of the files in the archive
     * @since 1.0
     */
    public function getFiles()
    {
        return $this->getAdapter()->getFiles();
    }

    /**
     * returns the number of files in the archive
     *
     * @return int the total files in the archive
     * @since 1.0
     */
    public function getFilesCount()
    {
        return $this->getAdapter()->getFilesCount();
    }

    /**
     * returns the archive comment from the adapter
     *
     * @return string the archive comment
     * @since 1.0
     */
    public function getComment()
    {
        return $this->getAdapter()->getComment();
    }

    /**
     * @param string $comment the new comment to set to the archive
     *
     * @return Adapter
     * @since 1.0
     */
    public function setComment($comment)
    {
        return $this->getAdapter()->setComment($comment);
    }

    /**
     * adds a new folder to the archive recursively
     *
     * @param string $path   the full path of the directory in the file system
     * @param string $parent the relative path of the parent in the archive
     *
     * @return Adapter the adapter instance
     * @since 1.0
     */
    public function addFolder($path, $parent = null)
    {
        return $this->getAdapter()->addFolder($path, $parent);
    }

    /**
     * adds a new file into the archive
     *
     * @param string $path      the full path of the file in the file system
     * @param string $localName the local name of the file in the archive
     * @param int    $start     the start point to read from the file
     * @param int    $length    the end point to read from the file
     *
     * @return bool was the file added?
     * @since 1.0
     */
    public function addFile($path, $localName = null, $start = 0, $length = null)
    {
        return $this->getAdapter()->addFile($path, $localName, $start, $length);
    }

    /**
     * refreshes the archive in the adapter
     *
     * @return Adapter the adapter with updated archive
     * @since 1.0
     */
    public function updateArchive()
    {
        return $this->getAdapter()->updateArchive();
    }

    /**
     * removes a file from the archive using a {@link File} Object
     *
     * @param File $file the File Object to remove
     *
     * @return bool was the file removed?
     * @since 1.0
     */
    public function removeFileByObject(File $file)
    {
        return $this->getAdapter()->removeFileByObject($file);
    }

    /**
     * removes a file from the archive using the file name in the archive
     *
     * @param string $name the file name
     *
     * @return bool was the file removed?
     * @since 1.0
     */
    public function removeFileByName($name)
    {
        return $this->getAdapter()->removeFileByName($name);
    }

    /**
     * adds files using
     *
     * @param  string $glob    the glob pattern
     * @param int     $flags   glob flags
     * @param array   $options An associative array of options. Available options are:
     *                         <p>
     *                         "add_path"
     *                         </p>
     *                         <p>
     *                         Prefix to prepend when translating to the local path of the file within
     *                         the archive. This is applied after any remove operations defined by the
     *                         "remove_path" or "remove_all_path"
     *                         options.
     *                         </p>
     *
     * @return bool was glob added?
     * @since 1.0
     */
    public function addGlob($glob, $flags = GLOB_BRACE, $options = array())
    {
        return $this->getAdapter()->addGlob($glob, $flags, $options);
    }

    /**
     * @param string $pattern   the regular expression pattern
     * @param string $directory the path of the directory
     * @param array  $options   An associative array of options accepted by <b>ZipArchive::addGlob</b>.
     *                          </p>
     *
     * @return bool was pattern added?
     * @since 1.0
     */
    public function addPattern($pattern, $directory, $options = array())
    {
        return $this->getAdapter()->addPattern($pattern, $directory, $options);
    }

    /**
     * creates a new archive from the current archive with the new format specified
     *
     * @param string $format the format of the compression
     *
     * @return Adapter the resulting adapter
     * @since 1.0
     */
    public function compress($format)
    {
        return $this->getAdapter()->compress($format);
    }
}