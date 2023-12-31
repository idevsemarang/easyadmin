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
        $columnNameInDb = collect($this->headers)->pluck('name')->toArray();

        foreach ($reader->getSheetIterator() as $sheet) {
            foreach ($sheet->getRowIterator() as $hKey => $row) {
                $cells = $row->getCells();

                if ($hKey == 1) {
                    foreach ($cells as $key => $cell) {
                       // $formatCell = str_replace(" ", $this->columnSeparator, strtolower($cell));

                        if (in_array($cell->getValue(), $columnNameInDb)) {
                            $allowedIndex[] = $key;
                        }
                    }
                } else {
                    $fields = [];
                    $updateByCols = [];
                    
                    foreach ($allowedIndex as $key => $ai) {
                        $fields[$this->headers[$key]['column']] = $cells[$ai]->getValue();

                        if( array_key_exists('relation', $this->headers[$key])){
                            $rel = $this->headers[$key]['relation'];
                            $mRelation = $rel['model']::where($rel['primary_attribute'], $cells[$ai]->getValue())->first();
                            $fields[$this->headers[$key]['column']] = ($mRelation) ? $mRelation->{$rel['primary_key']} : null;
                        }
                        if( array_key_exists('format', $this->headers[$key])){
                            if($this->headers[$key]['format'] == 'transformJson'){
                                $fields[$this->headers[$key]['column']] = $this->transformJson($cells[$ai]->getValue());
                            }
                        }
                        if( array_key_exists('relation_multi_value', $this->headers[$key])){
                            $rel = $this->headers[$key]['relation_multi_value'];
                            $currentArr = explode(', ',$cells[$ai]->getValue());
                            $mRelation = $rel['model']::whereIn($rel['primary_attribute'], $currentArr)->pluck($rel['primary_key']);
                            $fields[$this->headers[$key]['column']] = json_encode($mRelation);
                        }

                        if (in_array($this->headers[$key]['column'], $this->primaryKeys)) {
                            $updateByCols[$this->headers[$key]['column']] = $cells[$ai]->getValue();
                        }
                    }

                    $this->model::updateOrCreate($updateByCols, $fields);
                }
            }
        }

        $reader->close();

        unlink($filepath);
    }


    private function transformJson($value)
    {
        $arr = explode(', ', $value);
        $json = json_encode($arr);

        return $json;
    }

}
