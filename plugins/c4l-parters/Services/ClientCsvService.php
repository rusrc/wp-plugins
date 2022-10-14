<?php

namespace C4lPartners\Services;

if (!class_exists('CsvService')) {
    require_once __DIR__ . DIRECTORY_SEPARATOR . 'CsvService.php';
}

use C4lPartners\Models\Client;
use C4lPartners\Repositories\ClientRepository;

class ClientCsvService extends CsvService
{
    private ClientRepository $_clientRepository;

    public function __construct(ClientRepository $clientRepository /*, array $validHeaderColumns*/)
    {
        $this->_clientRepository = $clientRepository;
    }

    /**
     * UploadCsv
     *
     * @param  array $file - $_FILES['csv-file']
     * @return void
     */
    public function UploadClientsFromCsv(array $file, int $partner_id)
    {
        $uploadDir =  $this->GetUploadFolder();
        $fileName = date('YmdHis', time());
        $fullPath = $uploadDir . DIRECTORY_SEPARATOR . basename("{$fileName}.csv");

        if (move_uploaded_file($file['tmp_name'], $fullPath)) {
            // "Файл корректен и был успешно загружен.\n";
            return $this->InsertDataFromCsv($fullPath, $partner_id);
        }

        return false;
    }

    /**
     * @param $fullPath
     */
    public function InsertDataFromCsv($fullPath, int $partner_id)
    {
        // Check file delimiter
        $this->IsDelimiterValid($fullPath);

        // Valid header for CSV file
        $validHeaderColumns = array(
            0 => "name",
            1 => "login",
            2 => "password"
        );

        // Check columns and fields
        $this->ComparevalidHeaderColumnsColumnsWithObjectProperties($validHeaderColumns, new Client());

        // If all ok try insert
        $row = 1;
        if (($handle = fopen($fullPath, "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
                // Header first row
                if ($row === 1) {
                    $this->IsHeaderValid($data, $validHeaderColumns);
                } else {
                    $client = new Client();

                    foreach ($validHeaderColumns as $index => $value) {
                        $client->{$value} = $data[$index];
                    }

                    $client->partner_id = $partner_id;
                    $this->_clientRepository->InsertIfNotExists($client);
                }
                $row++;
            }

            fclose($handle);
            return true;
        }

        return false;
    }
}
