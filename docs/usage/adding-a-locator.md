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

## Sequencing configuration

When working with multiple packages it can be desired to sequence the loading order
of configuration. This can be done by sequencing configuration.

Sequencing configuration has a few requirements. Both packages need to have a defined
name in the `locator` and the sequencing package needs to have a sequence array.
The definition of the main package would look like the following:

```php
<?php

use GrizzIt\Configuration\Component\Configuration\PackageLocator;

PackageLocator::registerLocation(__DIR__, 'GrizzIt_Configuration');

```

In order to overwrite the configuration from this package, the `locator` of the
overwriting package should look like the following example:
```php
<?php

use GrizzIt\Configuration\Component\Configuration\PackageLocator;

PackageLocator::registerLocation(
    __DIR__,
    'MyVendor_Configuration',
    ['GrizzIt_Configuration']
);

```

This will tell the configuration compiler to prefer the configuration of the second
package over that of the original package.

## Good practices for sequencing

When sequencing configuration, it is advised to require the overwriting package
in the `composer.json` file. When configuration changes in the future this can
break the overwrite. To enforce this behaviour, a file should be created which
contians the package name, so this file can be included by the other package.
For example, an interface can be created for the package name, e.g.:
```php
<?php

namespace GrizzIt\Configuration\Common;

interface GrizzItConfigurationPackage
{
    public const PACKAGE_NAME = 'GrizzIt_Configuration'
}

```

When defining the locator package, it is possible to also do it this way:
```php
<?php

use GrizzIt\Configuration\Component\Configuration\PackageLocator;
use GrizzIt\Configuration\Common\GrizzItConfigurationPackage;

PackageLocator::registerLocation(
    __DIR__,
    GrizzItConfigurationPackage::PACKAGE_NAME
);

```

And then when sequencing the package:
```php
<?php

use GrizzIt\Configuration\Component\Configuration\PackageLocator;
use GrizzIt\Configuration\Common\GrizzItConfigurationPackage;

PackageLocator::registerLocation(
    __DIR__,
    'MyVendor_Configuration',
    [GrizzItConfigurationPackage::PACKAGE_NAME]
);

```

This will create a hard dependency on an object.

## Further reading

[Back to usage index](index.md)

[Creating configuration](creating-configuration.md)

[Compiling configuration](compiling-configuration.md)
