# API Based Plugin
A simple plugin that retrieves data from a remote API endpoint, and makes that data accessible/retrievable from an API endpoint on the WordPress site the plugin is installed on. The data will be displayed via a custom block and on an admin WordPress page as described. A simple WP CLI command is also present.
## Requirements

- [PHP](https://secure.php.net/manual/en/install.php) 8.1
- [WordPress](https://wordpress.org/) 6.0
- [Composer](https://getcomposer.org/) 2.6
- [Node.js](http://nodejs.org/) 18

## How to use

### Installation

1. Clone API Based Plugin to WordPress plugins directory (`wp-content/plugins`)

2. Setup php dependencies:

```bash
composer install
```

3. Setup node dependencies and dev mode build:

```bash
npm run install-dev
```

<!-- 5. Edit theme config object in `gulpfile.js`. Change theme name, theme prefix, main class, etc.


5. Run replace names command:

```bash
npm run replaceNames
```
-->
### Build commands

- Build minified assets for production:

```bash
npm run prod
```

- Build assets with source maps for development:

```bash
npm run dev
```

- Start watcher with browser sync for development:

```bash
npm run watch
```

<details>
  <summary>Yarn commands</summary>

- `yarn` - setup node dependencies;
- `yarn prod` - build minified assets;
- `yarn dev`- build assets with source maps (for development);
- `yarn watch`- start watcher;
- `yarn browser-sync` - start watcher with browser sync;

</details>

## Blocks

### Overview

 Gutenberg Blocks in the `blocks/` folder are loaded with the autoloader. That is, you can simply create a folder of a new block with the necessary files and this blocks will be automatically available.

## REST API endpoints
REST API endpoints are available under `abp/v1` namespace

```bash
/wp-json/abp/v1/api-data
```

## WP-CLI

Command to refresh API Table data:

```bash
wp fetch-api-data
```
