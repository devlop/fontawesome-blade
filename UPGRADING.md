# From v1 to v2

- The config file have been renamed from `fontawesome-blade.php` to `fontawesome.php` (the old config file will still work until v3).
- The package config option have been renamed from `package` to `path`, and expects the full path to the /svgs folder, and not only to the package root (the old config option `package` will still work until v3).
- The blade components are now namespaced as `fa`, use `<x-fa::solid ...>` instead of the old `<x-fa.solid ...>` (the old component will still work until v3).
- The default path now refers to `@fortawesome/fontawesome-free` instead of `@fortawesome/fontawesome-pro`, publish the config to change to your package of choice.
