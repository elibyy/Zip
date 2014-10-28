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

/**
 * Class Creator
 * this class is just removing the file existent check
 *
 * @version 1.0
 * @author  elibyy <elibyy@eyurl.com>
 * @package Elibyy
 */
class Creator extends
    Reader
{

    /**
     * initiate a new instance of the creator with the file specified
     *
     * @param string $file the file to load with the reader
     *
     * @throws \RuntimeException if no adapter found
     */
    public function __construct($file)
    {
        $this->adapter = $this->_getAdapter($file);
        $this->adapter->open($file);
    }
}
