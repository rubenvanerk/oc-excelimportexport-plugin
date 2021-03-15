<?php namespace WRvE\ExcelImportExport\Behaviors;

use Backend\Behaviors\ImportExportController;
use October\Rain\Database\Models\DeferredBinding;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
use System\Models\File;

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

        if ($ext === 'csv' || mime_content_type($path) === 'text/csv' || mime_content_type($path) === 'text/plain') {
            return $path;
        }

        $tempCsvPath = $path . '.csv';

        $reader = new Xlsx();
        $spreadsheet = $reader->load($path);
        $writer = new Csv($spreadsheet);
        $writer->setSheetIndex(0);
        $writer->save($tempCsvPath);

        $fileModel = $this->getFileModel();
        $disk = $fileModel->getDisk();
        $disk->put($fileModel->getDiskPath() . '.csv', file_get_contents($tempCsvPath));
        $fileModel->disk_name = $fileModel->disk_name . '.csv';
        $fileModel->save();

        return $path . '.csv';
    }

    /**
     * @return File
     */
    private function getFileModel()
    {
        $sessionKey = $this->importUploadFormWidget->getSessionKey();

        $deferredBinding = DeferredBinding::where('session_key', $sessionKey)
            ->orderBy('id', 'desc')
            ->where('master_field', 'import_file')
            ->first();

        return $deferredBinding->slave_type::find($deferredBinding->slave_id);
    }
}
