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
 * Interface File
 *
 * @version 1.0
 * @author  elibyy <elibyy@eyurl.com>
 * @package Elibyy\General
 */
interface File
{

    /**
     * @param array|string $args
     * @param Adapter      $adapter
     */
    public function __construct($args, Adapter $adapter);

    /**
     * @return mixed
     * @since 1.0
     */
    public function getName();

    /**
     * @return mixed
     * @since 1.0
     */
    public function getExtension();

    /**
     * @param null $format
     *
     * @return mixed
     * @since 1.0
     */
    public function getModifiedAt($format = null);

    /**
     * @return mixed
     * @since 1.0
     */
    public function getIndex();

    /**
     * @return mixed
     * @since 1.0
     */
    public function getSize();

    /**
     * @return mixed
     * @since 1.0
     */
    public function getCompressedSize();

    /**
     * @return mixed
     * @since 1.0
     */
    public function getCrc();

    /**
     * @return mixed
     * @since 1.0
     */
    public function getCompressionMethod();

    /**
     * @return mixed
     * @since 1.0
     */
    public function getStringCompressionMethod();

    /**
     * @param $comment
     *
     * @return mixed
     * @since 1.0
     */
    public function setComment($comment);

    /**
     * @return mixed
     * @since 1.0
     */
    public function getComment();

    /**
     * @return mixed
     * @since 1.0
     */
    public function getAdapter();
}
