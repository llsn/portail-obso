# clue/graph-composer [![Build Status](https://travis-ci.org/clue/graph-composer.png?branch=master)](https://travis-ci.org/clue/graph-composer)

Graph visualization for your project's `composer.json` and its dependencies:

![dependency graph for graph-composer](https://cloud.githubusercontent.com/assets/776829/11199047/46dd4dd2-8cca-11e5-845f-cbe485764f56.png)

**Table of contents**

* [Usage](#usage)
  * [graph-composer show](#graph-composer-show)
  * [graph-composer export](#graph-composer-export)
* [Install](#install)
  * [As a phar (recommended)](#as-a-phar-recommended)
    * [Updating phar](#updating-phar)
  * [Installation using Composer](#installation-using-composer)
    * [Updating dependency](#updating-dependency)
  * [Manual Installation from Source](#manual-installation-from-source)
    * [Updating manually](#updating-manually)
* [License](#license)

## Usage

Once clue/graph-composer is [installed](#install), you can use it via command line like this.

### graph-composer show

The `show` command creates a dependency graph for the given project path and opens
the default desktop image viewer for you:

```bash
$ php graph-composer.phar show ~/path/to/your/project
```

*   It accepts an optional argument which is the path to your project directory or composer.json file
    (defaults to checking the current directory for a composer.json file).

*   You may optionally pass an `--format=[svg/svgz/png/jpeg/...]` option to set
    the image type (defaults to `svg`).

### graph-composer export

The `export` command works very much like the `show` command, but instead of opening your
default image viewer, it will write the resulting graph to STDOUT or into an image file:

```bash
$ php graph-composer.phar export ~/path/to/your/project
```

*   It accepts an optional argument which is the path to your project directory or composer.json file
    (defaults to checking the current directory for a composer.json file).

*   It accepts an additional optional argument which is the path to write the resulting image to.
    Its file extension
    also sets the image format (unless you also explicitly pass the `--format` option). Example call:

    ```bash
    $ php graph-composer.phar export ~/path/to/your/project export.png
    ```

    If this argument is not given, it defaults to writing to STDOUT, which may
    be useful for scripting purposes:

    ```bash
    $ php graph-composer.phar export ~/path/to/your/project | base64
    ```

*   You may optionally pass an `--format=[svg/svgz/png/jpeg/...]` option to set
    the image type (defaults to `svg`).

## Install

You can grab a copy of clue/graph-composer in either of the following ways.

### As a phar (recommended)

You can simply download a pre-compiled and ready-to-use version as a Phar
to any directory.
Simply download the latest `graph-composer.phar` file from our
[releases page](https://github.com/clue/graph-composer/releases):

[Latest release](https://github.com/clue/graph-composer/releases/latest)

Additionally, you'll have to install GraphViz (`dot` executable).
Users of Debian/Ubuntu-based distributions may simply invoke:

```bash
$ sudo apt-get install graphviz
```

Windows users have to [download GraphViZ for Windows](http://www.graphviz.org/Download_windows.php) and remaining
users should install from [GraphViz homepage](http://www.graphviz.org/Download.php).

That's it already. You can now verify everything works by running this:

```bash
$ cd ~/Downloads
$ php graph-composer.phar --version
```

> If you prefer a global (system-wide) installation without having to type the `.phar` extension
each time, you may simply invoke:
> 
> ```bash
> $ chmod 0755 graph-composer.phar
> $ sudo mv graph-composer.phar /usr/local/bin/graph-composer`
> ```
>
> You can verify everything works by running:
> 
> ```bash
> $ graph-composer --version
> ```

#### Updating phar

There's no separate `update` procedure, simply download the latest release again
and overwrite the existing phar.

### Installation using Composer

Alternatively, you can also install graph-composer as part of your development dependencies.
You will likely want to use the `require-dev` section to exclude graph-composer in your production environment.

This method also requires PHP 5.3+, GraphViz and, of course, Composer.

You can either modify your `composer.json` manually or run the following command to include the latest tagged release:

```bash
$ composer require --dev clue/graph-composer
```

Now you should be able to invoke the following command in your project root:

```bash
$ ./vendor/bin/graph-composer show
```

Alternatively, you can install this globally for your user by running:

```bash
$ composer global require clue/graph-composer
```

Now, assuming you have `~/.composer/vendor/bin` in your path, you can invoke the following command:

```bash
$ graph-composer show ~/path/to/your/project
```

> Note: You should only invoke and rely on the main graph-composer bin file.
Installing this project as a non-dev dependency in order to use its
source code as a library is *not supported*.

#### Updating dependency

Just run `composer update clue/graph-composer` to update to the latest release.

If you installed it globally via composer you can run `composer global update clue/graph-composer` instead.

### Manual Installation from Source

This project requires PHP 5.3+, Composer and GraphViz:

```bash
$ sudo apt-get install php5-cli graphviz
$ git clone https://github.com/clue/graph-composer.git
$ cd graph-composer
$ curl -s https://getcomposer.org/installer | php
$ php composer.phar install
```

You can now verify everything works by running graph-composer like this:

```bash
$ php bin/graph-composer show
```

> If you want to build the above mentioned `graph-composer.phar` yourself, you have
to install [clue/phar-composer](https://github.com/clue/phar-composer#install)
and can simply invoke:
>
> ```bash
> $ php phar-composer.phar build ~/workspace/graph-composer
> ```

#### Updating manually

```bash
$ git pull
$ php composer.phar install
```

## License

MIT
