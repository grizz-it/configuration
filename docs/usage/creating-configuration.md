# GrizzIT Configuration - Creating configuration

## Choosing a directory
To create configuration for an application a few things need to be setup. A
directory needs to be chosen where all configuration will be stored. For example
`configuration/<name-of-the-configuration-type>`. By choosing this format, all
configuration can be put into one directory, and everything for the same scope
will be using the same directory. E.g.: when creating database configuration
this will be become `configuration/database`, this directory needs to be created
in the root of the package.

## Encoding
The encoding of the files should be supported by `ulrack/vfs`. This package will
be responsible for the encoding and decoding of the files within the directory
(or an extension to the package should be supporting the format).

## Creating the configuration
When the directory is chosen and the supported encoding types are found, the
files can be creating in the directory. E.g.: a database connection file can be
created at `configuration/database/connection.json` with the contents:
```json
{
    "host": "localhost",
    "user": "username",
    "password": "password",
    "type": "mysql",
    "port": 3306,
    "database": "my-database",
    "key": "main"
}
```

When this file is added a locator needs to be added. If the package is also
using the configuration, then it is also responsible for compiling the it.

## Further reading

[Back to usage index](index.md)

[Adding a locator](adding-a-locator.md)

[Compiling configuration](compiling-configuration.md)
