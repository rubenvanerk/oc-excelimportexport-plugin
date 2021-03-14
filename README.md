# Excel Import Export for October CMS

This plugin adds Excel support to the Import Export behavior of October CMS.

## Usage

Instead of implementing `Backend.Behaviors.ImportExportController`, use the one from this plugin like so:

```
public $implement = [
    'WRvE\ExcelImportExport\Behaviors\ExcelImportExportController',
];
```
