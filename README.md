Code generators
===============

This is a collection of Symfony 2 code generators based on nikic's PHP-Parser.
These generators can be way more flexible than the ones provided by SensioGeneratorBundle
due to their event-based nature.

First, all generators utilize an event dispatcher to notify others of the changes. This allows
automatic tests/specs generation once a base generator has fired.

[Here the `ControllerGenerator` dispatches a `ControllerGenerated` event](https://github.com/kix/generator-bundle/blob/master/src/Generator/ControllerGenerator.php#L56) 
which then triggers [TwigViewGenerator](https://github.com/kix/generator-bundle/blob/master/src/Generator/TwigViewGenerator.php#L20) and
[UnitTestGenerator](https://github.com/kix/generator-bundle/blob/master/src/Generator/UnitTestGenerator.php#L36)
to generate tests and Twig templates.

Second, an AST processor dispatcher is built in. You can hook up your own `ProcessorInterface`
implementations that would modify the syntax tree before it's dumped into a file.

Currently, the implementation is lacking some things:

- ~~PHPParser does not yet allow dumping comment blocks, so there's no way to generate
annotations yet.~~
- The ControllerGenerator is mostly just proof-of-concept.
- AST processors have not really been tested yet.
- Everything needs way more flexibility.
