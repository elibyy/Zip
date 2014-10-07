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
namespace Elibyy\General;

/**
 * Interface Adapter
 * the adapter interface for adapters to implement
 *
 * @package Elibyy\General
 */
interface Adapter
{

    /**
     * @since 1.0
     */
    public function __construct();

    /**
     * opens the archive file or creates one by the file name supplied
     *
     * @param string $file  the Archive filename
     * @param int    $flags the flags depend on the adapter
     *
     * @return void
     * @since 1.0
     */
    public function open($file, $flags = 0);

    /**
     * returns an array of {@link File} object to iterate on
     *
     * @return File[] the array of the files in the archive
     * @since 1.0
     */
    public function getFiles();

    /**
     * return true if the unzipping was success else failure
     *
     * @param string       $destination
     * @param string|array $entries
     *
     * @return bool was it success ?
     * @since 1.0
     */
    public function unzip($destination = null, $entries = null);

    /**
     * returns the archive object depends on the adapter
     *
     * @return object the archive object instance depend on the adapter
     * @since 1.0
     */
    public function getArchive();

    /**
     * returns the archive file name
     *
     * @return string the archive file name
     * @since 1.0
     */
    public function getFilename();

    /**
     * returns the number of files in the archive
     *
     * @return int the number of files in the archive
     * @since 1.0
     */
    public function getFilesCount();

    /**
     * returns the archive comment if supported by the archive or false if not supported
     *
     * @return string|bool the comment of the archive
     * @since 1.0
     */
    public function getComment();

    /**
     * if supported by the adapter will set the new comment for the archive
     *
     * @param string $comment the new archive comment
     *
     * @return Adapter|bool the adapter instance on suceess else false
     * @since 1.0
     */
    public function setComment($comment);

    /**
     * adds a new folder recursively to the archive
     *
     * @param string $path   the folder full path
     * @param string $parent the parent path used for relative path in the archive
     *
     * @return Adapter
     * @since 1.0
     */
    public function addFolder($path, $parent = '');

    /**
     * adds a new file into the archive.
     * please note if $noUpdate is set to true you must run {@link Adapter#updateArchive()} manually,<br>
     * or your archive info might not be updated
     *
     * @param  string $path      the file full path
     * @param string  $localName the file local name in the archive
     * @param int     $start     the start position to start from when reading the file
     * @param null    $length    the end position to stop when reading the file
     * @param bool    $noUpdate  if set will not update the archive after adding the file
     *
     * @return bool was the file added?
     * @since 1.0
     */
    public function addFile($path, $localName = null, $start = 0, $length = null, $noUpdate = false);

    /**
     * updates the archive in the adapter to show latest changes
     *
     * @return $this
     * @since 1.0
     */
    public function updateArchive();

    /**
     * removes a file from the archive using a {@link File} Object
     *
     * @param File $file the File Object to remove
     *
     * @return bool was the file removed?
     * @since 1.0
     */
    public function removeFileByObject(File $file);

    /**
     * removes a file from the archive using the file name in the archive
     *
     * @param string $name the file name
     *
     * @return bool was the file removed?
     * @since 1.0
     */
    public function removeFileByName($name);

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
     * @return array the result of the glob
     * @since 1.0
     */
    public function addGlob($glob, $flags = GLOB_BRACE, $options = array());

    /**
     * @param string $pattern   the regular expression pattern
     * @param string $directory the path of the directory
     * @param array  $options   An associative array of options accepted by <b>ZipArchive::addGlob</b>.
     *                          </p>
     *
     * @return array the result of the pattern
     * @since 1.0
     */
    public function addPattern($pattern, $directory, $options = array());

    /**
     * checks if the file type is supported by the adapter
     *
     * @param string $type the file type
     *
     * @return bool is the type supported
     * @since 1.0
     */
    public static function supports($type);

    /**
     * @param string $format the format of the compression
     *
     * @return Adapter the resulting adapter
     * @since 1.0
     */
    public function compress($format);
}