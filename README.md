# Excel Import Export for October CMS

This plugin is a fork of the wrve/oc-excelimportexport-plugin. I'm working on it, and although it is functional, I do not recommend using it yet as I am still developing it.

Currently, it has the functionality to select the columns to be exported, which addresses a specific need of mine. In the future, I will work to make this a configurable element from the model. If you still wish to use it, please do so with the necessary caution.

Thanks to WRvE for such a great idea and the initial work.

## Installation

The original plugin can be installed through composer: `composer require wrve/oc-excelimportexport-plugin`

THIS FORK is installed manually:
 - Create a folder `plugins/wrve/excelimportexport`
 - Clone this repo inside the folder using the `.` option: `git clone https://github.com/sanPuerquitoProgramador/oc-excelimportexport-plugin.git .`
 - Run `composer install` inside the plugin folder

## Usage

Instead of implementing `Backend.Behaviors.ImportExportController`, use the one from this plugin like so:

```php
public $implement = [
    'WRvE\ExcelImportExport\Behaviors\ExcelImportExportController',
];
```