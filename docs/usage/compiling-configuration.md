# GrizzIT Configuration - Compiling configuration

When using configuration in an application, it all needs to be compiled,
interpreted and stored in a registry so it is accessible. This can be easily
done with the provided
[ConfigurationCompiler](../../src/Component/Compiler/ConfigurationCompiler.php).

## Locator
To compile the configuration, a [Locator](../../src/Dao/Locator.php) needs to be
created. To get all the configuration for a location, e.g.
`database` configuration in the `configuration/database` directory of packages,
instantiate the Locator like so:
```php
<?php

use Ulrack\Configuration\Dao\Locator;

$locator = new Locator('database', 'configuration/database');
```

## Compiling
After the locators are ready, the compiler can be instantiated and the locators
can be added to the object. After the initial setup, the configuration can be
compiled by invoking the `compile` method:

```php
<?php

use Ulrack\Configuration\Component\Compiler\ConfigurationCompiler;

$compiler = new ConfigurationCompiler(
    new LocalFileSystemDriver(/** @see: ulrack/vfs */)
);

$compiler->addLocator($locator);

$registry = $compiler->compile();
```

Any duplicate relative file paths will only be compiled once. If it occurs in a
different package as well, which is sequenced prior to the later loaded package,
then the later package's configuration will be ignored.

## Registry
The returned [Registry](../../src/Component/Registry/Registry.php) then contains
all the compiled configuration. The registry can return the specific `database`
configuration by calling `get` with the `database` key which will return an
array with all configuration.

```php
<?php

$registry->get('database');
```

The entire registry can also be converted to an array, by invoking the `toArray`
method. This can be useful for storing the compiled configuration into one
storage. It can also be loaded into the registry from this storage, by passing
the data to the `import` method.


## Further reading

[Back to usage index](index.md)

[Creating configuration](creating-configuration.md)

[Adding a locator](adding-a-locator.md)
