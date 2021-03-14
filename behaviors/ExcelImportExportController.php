<?php namespace WRvE\ExcelImportExport\Behaviors;

use Backend\Behaviors\ImportExportController;
use Backend\Widgets\Form;
use October\Rain\Database\Models\DeferredBinding;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Csv;

class ExcelImportExportController extends ImportExportController
{
    public function __construct($controller)
    {
        parent::__construct($controller);
        $this->viewPath = base_path() . '/modules/backend/behaviors/importexportcontroller/partials';
        $this->assetPath = '/modules/backend/behaviors/importexportcontroller/assets';
    }

    protected function createCsvReader($path)
    {
        $path = $this->convertToCsv($path);

        return parent::createCsvReader($path);
    }

    private function convertToCsv(string $path)
    {
        $ext = pathinfo($path, PATHINFO_EXTENSION);

        if ($ext === 'csv') {
            return $path;
        }

        $reader = new Xlsx();
        $spreadsheet = $reader->load($path);

        $writer = new Csv($spreadsheet);

        $writer->setSheetIndex(0);
        $writer->save($path . '.csv');

        $sessionKey = $this->importUploadFormWidget->getSessionKey();

        $deferredBinding = DeferredBinding::where('session_key', $sessionKey)
            ->orderBy('id', 'desc')
            ->where('master_field', 'import_file')
            ->first();

        $file = $deferredBinding->slave_type::find($deferredBinding->slave_id);

        $file->disk_name = pathinfo($path)['basename'] . '.csv';
        $file->save();

        return $path . '.csv';
    }
}
