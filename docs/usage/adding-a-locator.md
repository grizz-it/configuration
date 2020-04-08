# GrizzIT Configuration - Adding a locator

The [PackageLocator](../../src/Component/Configuration/PackageLocator.php) is a
static class which is used to register the location of packages. This location
is used by the compiler to find all configuration files. To register a package
add a `files` node to the `autoload` node in the `composer.json` file.
```json
{
    "autoload": {
        "files": ["locator.php"]
    }
}
```

Then create a `locator.php` file inside root of the package, with the following
content:
```php
<?php

use GrizzIt\Configuration\Component\Configuration\PackageLocator;

PackageLocator::registerLocation(__DIR__);

```

The package is now added to the locator and can be used by the compiler.

## Further reading

[Back to usage index](index.md)

[Creating configuration](creating-configuration.md)

[Compiling configuration](compiling-configuration.md)
