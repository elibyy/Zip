Documentation
=============

Introduction
------------

Elibyy/Zip is an Object Oriented PHP library that makes PHP Archive handling easier

this library currently supports

- `zip`_
- PHP zip
- `Phar`_
- `TAR`_
- `BZIP2`_
- `GZ`_

which is the following extensions
 - .zip
 - .phar
 - .tar
 - .bz2
 - .gz

Installation
------------

We rely on `composer`_ to use this library. If you do
not still use composer for your project, you can start with this ``composer.json``
at the root of your project :

.. code-block:: json

    {
        "require": {
            "elibyy/zip": "1.0"
        }
    }

Install composer :

.. code-block:: bash

    # Install composer
    curl -s http://getcomposer.org/installer | php
    # Upgrade your install
    php composer.phar install

You now just have to autoload the library to use it :

.. code-block:: php

    <?php
    require 'vendor/autoload.php';

    use Elibyy\Reader;

    $reader = new Reader('/path/to/file.zip');

This is a very short intro to composer.
If you ever experience an issue or want to know more about composer,
you will find help on their web site `composer`_.

Basic Usage
-----------

The Zippy library is very simple and consists of a collection of adapters that
take over for you the most common (de)compression operations (create, list
update, extract, delete) for the chosen format.

**Example usage**

.. code-block:: php

    <?php

    use Elibyy\Reader;
    use Elibyy\Creator;

    // extract an archive
    $reader = new Reader('/path/to/file.zip');
    $reader->extract('/destination/folder');

    //remove file from archive

    $reader->removeFileByName('file.txt');

    // create an archive
    $creator = new Creator('path/to/new.zip');
    $creator->addFolder('/path/to/dir/');


this library comes with a smart adapter locating by the specified file

**Creates or opens one archive**

.. code-block:: php

    <?php

    use Elibyy\Reader;
    use Elibyy\Creator;

    $reader = new Reader('/path/to/file.zip');
    $creator = new Creator('path/to/new.zip');

**Define your custom adapter**

currently the library doesn't support the addition of adapters not in the namespace of the adapters
``Elibyy\Adapters``
 but you can add in that namespace directory a new class that implements ``Elibyy\General\Adapter``
 or extends one of the existing Adapters

Handling Exceptions
-------------------

the library throws the following exceptions
``\RuntimeException`` if the file provided to the reader doesn't exist
``\RuntimeException`` if the adapter folder is missing
``\RuntimeException`` if no adapter found for the file provided

Report a bug
------------

If you experience an issue, please report it in our `issue tracker`_. Before
reporting an issue, please be sure that it is not already reported by browsing
open issues.

Contribute
----------

You find a bug and resolved it ? You added a feature and want to share ? You
found a typo in this doc and fixed it ? Feel free to send a `Pull Request`_ on
GitHub, we will be glad to merge your code.

Run tests
---------

Zippy relies on `PHPUnit`_ for unit tests. To run tests on your system, ensure
you have `PHPUnit`_ installed, and, at the root of Zippy execute it :

.. code-block:: bash

    phpunit

About
-----


License
-------

the LICENSE is provided in the archive

.. _composer: http://getcomposer.org/
.. _TAR: http://www.gnu.org/software/tar/manual/
.. _ZIP: http://www.info-zip.org/
.. _PHAR: http://php.net/manual/en/book.phar.php
.. _issue tracker: https://github.com/elibyy/Zip/issues
.. _Pull Request: http://help.github.com/send-pull-requests/
.. _PHPUnit: http://www.phpunit.de/manual/current/en/
.. _BZIP2: http://www.bzip.org/
.. _GZ: http://www.gzip.org/