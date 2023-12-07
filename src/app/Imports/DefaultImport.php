<?php

namespace Idev\EasyAdmin\app\Imports;

use OpenSpout\Reader\Common\Creator\ReaderFactory;

class DefaultImport 
{
    private $headers;
    private $fileExcel;
    private $columnSeparator;
    private $primaryKeys;
    private $model;

    public function __construct($attrs = [])
    {
        $this->fileExcel = $attrs['fileExcel'];
        $this->headers = $attrs['headers'];
        $this->primaryKeys = $attrs['primaryKeys'];
        $this->columnSeparator = $attrs['columnSeparator'] ?? "_";
        $this->model = $attrs['model'];
    }

    public function import()
    {
        $filename = "idev_" . time() . "." . $this->fileExcel->getClientOriginalExtension();
        $this->fileExcel->storeAs('temp', $filename, 'public');

        $filepath = storage_path('app/public/temp/'.$filename);

        $reader = ReaderFactory::createFromFile($filepath);
        $reader->open($filepath);

        $allowedIndex = [];

        foreach ($reader->getSheetIterator() as $sheet) {
            foreach ($sheet->getRowIterator() as $hKey => $row) {
                $cells = $row->getCells();

                if ($hKey == 1) {
                    foreach ($cells as $key => $cell) {
                        $formatCell = str_replace(" ", $this->columnSeparator, strtolower($cell));

                        if (in_array($formatCell, $this->headers)) {
                            $allowedIndex[] = $key;
                        }
                    }
                } else {
                    $fields = [];
                    $updateByCols = [];

                    foreach ($allowedIndex as $key => $ai) {
                        $fields[$this->headers[$key]] = $cells[$ai];

                        if (in_array($this->headers[$key], $this->primaryKeys)) {
                            $updateByCols[$this->headers[$key]] = $cells[$ai];
                        }
                    }

                    $this->model::updateOrCreate($updateByCols, $fields);
                }
            }
        }

        $reader->close();

        unlink($filepath);
    }

}
