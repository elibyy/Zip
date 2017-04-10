*This is a fork of https://github.com/elibyy/Zip which was unexpectedly deleted and abandoned without any prior notice.*

[![Build Status](https://travis-ci.org/ticketpark/elibyy-zip.svg?branch=master)](https://travis-ci.org/elibyy/Zip)
[![Docs](https://readthedocs.org/projects/zip/badge/?version=latest)](http://zip.rtfd.org/)
#Elibyy Zip
A Object-Oriented PHP library to manipulate archives

##Adapters

this library currently supports

- zip
- PHP zip
- Phar
- TAR
- BZIP2
- GZ

which is the following extensions
 - .zip
 - .phar
 - .tar
 - .bz2
 - .gz
 
 ##API Example
 
```php
 use Elibyy\Reader
 $reader = new Reader('/path/to/file.zip');
 $reader->getFiles(); #will return File[]
 $reader->addFile('/path/to/file.txt','my/inner/path/file.txt'); #will add a file to the archive with path my/inner/path
 $reader->removeFileByName('file.txt');
```

Documentation hosted at [read the docs](http://zip.rtfd.org/) !