<?php

namespace C4lPartners\Services;

use C4lPartners\C4lException\C4lException;

class CsvService
{
    public function IsDelimiterValid($filePath, $capture_limit_in_kb = 10): void
    {
        $array = $this->AnalyseFile($filePath, $capture_limit_in_kb);
        $result = $array['delimiter']['value'] === ';';

        if (!$result) {
            throw new C4lException('Проверьте разделитель. Должен быть ";"');
        }
    }

    /**
     * https://www.php.net/manual/ru/function.fgetcsv.php
     * 
     * usage $Array = analyse_file('/www/files/file.csv', 10);
     * 
     *  example usable parts
     *  $Array['delimiter']['value'] => ,
     *  $Array['line_ending']['value'] => \r\n
     * 
     * @return array
     */
    public function AnalyseFile($file, $capture_limit_in_kb = 10)
    {
        // capture starting memory usage
        $output['peak_mem']['start']    = memory_get_peak_usage(true);

        // log the limit how much of the file was sampled (in Kb)
        $output['read_kb']                 = $capture_limit_in_kb;

        // read in file
        $fh = fopen($file, 'r');
        $contents = fread($fh, ($capture_limit_in_kb * 1024)); // in KB
        fclose($fh);

        // specify allowed field delimiters
        $delimiters = array(
            'comma'     => ',',
            'semicolon' => ';',
            'tab'         => "\t",
            'pipe'         => '|',
            'colon'     => ':'
        );

        // specify allowed line endings
        $line_endings = array(
            'rn'         => "\r\n",
            'n'         => "\n",
            'r'         => "\r",
            'nr'         => "\n\r"
        );

        // loop and count each line ending instance
        foreach ($line_endings as $key => $value) {
            $line_result[$key] = substr_count($contents, $value);
        }

        // sort by largest array value
        asort($line_result);

        // log to output array
        $output['line_ending']['results']     = $line_result;
        $output['line_ending']['count']     = end($line_result);
        $output['line_ending']['key']         = key($line_result);
        $output['line_ending']['value']     = $line_endings[$output['line_ending']['key']];
        $lines = explode($output['line_ending']['value'], $contents);

        // remove last line of array, as this maybe incomplete?
        array_pop($lines);

        // create a string from the legal lines
        $complete_lines = implode(' ', $lines);

        // log statistics to output array
        $output['lines']['count']     = count($lines);
        $output['lines']['length']     = strlen($complete_lines);

        // loop and count each delimiter instance
        foreach ($delimiters as $delimiter_key => $delimiter) {
            $delimiter_result[$delimiter_key] = substr_count($complete_lines, $delimiter);
        }

        // sort by largest array value
        asort($delimiter_result);

        // log statistics to output array with largest counts as the value
        $output['delimiter']['results']     = $delimiter_result;
        $output['delimiter']['count']         = end($delimiter_result);
        $output['delimiter']['key']         = key($delimiter_result);
        $output['delimiter']['value']         = $delimiters[$output['delimiter']['key']];

        // capture ending memory usage
        $output['peak_mem']['end'] = memory_get_peak_usage(true);
        return $output;
    }


    /**
     * is header valid
     *
     * @param  mixed $currentheader
     * @param  mixed $validHeaderColumns
     * @return void
     * @exception C4lException
     */
    public function IsHeaderValid(array $currentheader, array $validHeaderColumns): void
    {
        $isValid = false;

        foreach ($validHeaderColumns as $columnNumber => $validValue) {
            $isValid = strtoupper($validValue) === strtoupper($currentheader[$columnNumber]);

            if (!$isValid) {
                $msgError = "Проверьте шабку CSV. Должно быть значение '{$validValue}', но значение в файле '{$currentheader[$columnNumber]}'.";

                throw new C4lException($msgError);
            };
        }
    }

    /**
     * Compare valid header columns with object properties
     *
     * @param  mixed $validHeaderColumns - show valid columns
     * @param  mixed $model - the object that should be checked
     * @return void
     */
    public function ComparevalidHeaderColumnsColumnsWithObjectProperties($validHeaderColumns, $model)
    {
        $properties = get_object_vars($model);
        foreach ($validHeaderColumns as $columnValue) {
            if (!array_key_exists($columnValue, $properties)) {
                $className = get_class($model);
                $columns = array_reduce($validHeaderColumns, fn ($prev, $next) => "$prev <br /> $next");
                $propertiesString = array_reduce($properties, fn ($prev, $next) => "$prev <br /> $next");

                throw new C4lException(
                    "Объект '{$className}' несодержит свойство '{$columnValue}', которое есть в файле, но нет у объекта'. 
                    <br /> Необходимо, чтобы колонка в файле и свойство объекта совпадали. 
                    <br /> Эти свойства должны совпадать: <br /> {$columns}
                    <br />
                    <br /> Свойства у объетка: <br /> {$propertiesString}"
                );
            }
        }
    }

    /**
     * Return upload folder for csv files
     */
    public function GetUploadFolder()
    {
        $upload = wp_get_upload_dir();
        $uploadDir =  $upload['basedir'] . DIRECTORY_SEPARATOR . 'c4l_csv_files';;

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0700);
        }

        return $uploadDir;
    }
}
