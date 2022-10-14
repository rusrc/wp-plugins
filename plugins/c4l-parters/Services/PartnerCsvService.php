<?php

namespace C4lPartners\Services;

if (!class_exists('CsvService')) {
    require_once __DIR__ . DIRECTORY_SEPARATOR . 'CsvService.php';
}

use C4lPartners\Models\Partner;
use C4lPartners\Repositories\PartnerRepository;

class PartnerCsvService extends CsvService
{
    private PartnerRepository $_partnerRepository;

    public function __construct(PartnerRepository $partnerRepository)
    {
        $this->_partnerRepository = $partnerRepository;
    }

    /**
     * UploadCsv
     *
     * @param  array $file - $_FILES['csv-file']
     * @return void
     */
    public function UploadClientsFromCsv(array $file)
    {
        $uploadDir = plugin_dir_path(__DIR__) . DIRECTORY_SEPARATOR . 'Uploads';
        $fileName = date('YmdHis', time()); //. '_' . uniqid();
        $fullPath = $uploadDir . DIRECTORY_SEPARATOR . basename("{$fileName}.csv");

        if (move_uploaded_file($file['tmp_name'], $fullPath)) {
            // "Файл корректен и был успешно загружен.\n";
            return $this->InsertDataFromCsv($fullPath);
        }

        return false;
    }

    /**
     * @param $fullPath
     */
    public function InsertDataFromCsv($fullPath)
    {
        // Check file delimiter
        $this->IsDelimiterValid($fullPath);

        // Valid header for CSV file
        $validHeaderColumns = array(
            0 => "link",
            1 => "name"
        );

        // Check columns and fields
        $this->ComparevalidHeaderColumnsColumnsWithObjectProperties($validHeaderColumns, new Partner());

        // If all ok try insert
        $row = 1;
        if (($handle = fopen($fullPath, "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
                // Header first row
                if ($row === 1) {
                    $this->IsHeaderValid($data, $validHeaderColumns);
                } else {
                    $partner = new Partner();

                    foreach ($validHeaderColumns as $index => $value) {
                        $partner->{$value} = $data[$index];
                    }

                    $this->_partnerRepository->InsertIfNotExists($partner);
                }
                $row++;
            }

            fclose($handle);
            return true;
        }

        return false;
    }
}
