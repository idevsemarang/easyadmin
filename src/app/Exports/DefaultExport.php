<?php

namespace Idev\EasyAdmin\app\Exports;

use OpenSpout\Common\Entity\Row;
use OpenSpout\Common\Entity\Style\CellAlignment;
use OpenSpout\Common\Entity\Style\Color;
use OpenSpout\Common\Entity\Style\Style;
use OpenSpout\Writer\Common\Creator\WriterFactory;
use OpenSpout\Common\Type;
use OpenSpout\Writer\Common\Creator\Style\StyleBuilder;
use OpenSpout\Writer\Common\Creator\WriterEntityFactory;

class DefaultExport
{
    private $filename;
    private $dataHeaders;
    private $dataQueries;
    private $headerBackgroundColor;
    private $headerTextColor;

    public function __construct($attribute)
    {
        $this->filename = $attribute['filename'] ?? "export.xlsx";
        $this->dataHeaders = $attribute['dataHeaders'];
        $this->dataQueries = $attribute['dataQueries'];
        $this->headerTextColor = $attribute['headerTextColor'] ?? "ffffff";
        $this->headerBackgroundColor = $attribute['headerBackgroundColor'] ?? "535353";
    }

    public function downloadExcel()
    {
        try {
            $filename = $this->filename;

            $writer = WriterFactory::createFromFile($filename);
            $writer->openToFile($filename);

            // Set headers
            $styleForHeader = new Style();
            $styleForHeader->setFontBold();
            $styleForHeader->setFontSize(11);
            $styleForHeader->setFontColor($this->headerTextColor);
            $styleForHeader->setShouldWrapText();
            $styleForHeader->setCellAlignment(CellAlignment::CENTER);
            $styleForHeader->setBackgroundColor($this->headerBackgroundColor);

            $styleForBody = new Style();
            $styleForBody->setFontSize(11);
            $styleForBody->setShouldWrapText(true);
            $styleForBody->setCellAlignment(CellAlignment::LEFT);
           
            $arrHeaders = [];
            foreach ($this->dataHeaders as $key => $hd) {
              $arrHeaders[] = $hd['name'];
            }

            $rowHeader = Row::fromValues($arrHeaders, $styleForHeader);
            $writer->addRow($rowHeader);

            // Add data
            foreach ($this->dataQueries as $key => $dq) {
                $arrBody = [];
                foreach ($this->dataHeaders as $key2 => $hd) {
                    if($hd['column'] == "#"){
                        $arrBody[] = $key+1;
                    }else{
                        $arrBody[] = $dq->{$hd['column']};
                    }
                }
                $rowBody = Row::fromValues($arrBody, $styleForBody);
                $writer->addRow($rowBody);
            }
            
            $writer->close();

            return response()->download($filename);

        } catch (\Exception $e) {
            $response = [
                'status' => false,
                'message' => $e->getMessage(),
            ];

            return $response;
        }
    }
}
