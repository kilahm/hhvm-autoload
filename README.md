Autoload Map Generator for HHVM
===============================

What is this?
-------------

HHVM has a handy alternative to Zend's `__autoload` and `spl_autoload_register`,
`fb_autoload_map`:

    fb_autoload_map(
      array(
        'classes' => array(
          'SomeClass' => 'some_class.php',
          // ...
        ),
        'function' => array(
          'some_function' => 'some_function.php',
          // ...
        ),
        'constant' => array(
          'FOO_BAR' => 'foo_constants.php',
          // ...
        ),
      ),
      'prefix/',
    );

It supports classes, functions, and constants, whereas
`__autoload`/`spl_autoload_register` only support classes. You might also
find it performs better.

This is an HHVM extension that provides an easy way to generate this map.

How do I use it?
----------------

Once you've built it:

    $map = FE_AutoloadMapGenerator::getMapForTree(
      '/path/to/autoloadables/',
    );
    fb_autoload_map($map, $_SERVER['PHP_ROOT'].'/autoloadables/');
    SomeAutoloadedClass::doSomething();

or, maybe:

    $map = FE_AutoloadMapGenerator::getMapForTree(
      '/path/to/autoloadables/',
      FE_AutoloadMapGenerator::ALLOW_SHORT_TAGS |
      FE_AutoloadMapGenerator::ALLOW_XHP_SYNTAX,
    );
    fb_autoload_map($map, $_SERVER['PHP_ROOT'].'/autoloadables/');
    SomeAutoloadedClass::doSomething();

You probably want to cache the result rather than running this on every request ;)

How do I install it?
--------------------

You need to have built HHVM from source - then:

    export HPHP_HOME=/path/to/hhvm
    $HPHP_HOME/hphp/tools/hphpize/hphpize
    cmake .
    make

You'll need to then add this to DynamicExtensions in config.hdf, or specify
it on the command line (see test.sh for an example of this);
