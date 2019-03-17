<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;

class CsvCreator
{
    protected $path = '';
    protected $data = [];
    private $fields = [];

    /**
     * @param string $path
     * @return $this
     */
    public function setPath($path)
    {
        $this->path = $path;
        return $this;
    }

    /**
     * @param array $data
     * @return CsvCreator
     */
    public function setData(array $data)
    {
        $this->data = $data;
        return $this;
    }

    /**
     * @param array $fields
     * @return $this
     */
    public function useFields(array $fields)
    {
        $this->fields = $fields;
        return $this;
    }

    /**
     * Creates a .csv file, using the previously given data.
     * Return created file name
     * @return bool|string
     * @throws \Exception
     */
    public function make()
    {
        if (!$this->validateCurrentPath()) {
            throw new \Exception('Current path is not valid or does not exist.');
        }

        if (empty($this->data)) {
            throw new \Exception('Data provided is empty!');
        }

        $out = $this->arrayToCSV($this->data);

        $file_name = $this->path . '/' . date('m-d-Y_H-i-s') . '.csv';
        $file = fopen($file_name, "w");
        if (!$file) {
            throw new \Exception('File could not be opened or does not exists. Please check the read permissions.');
        }

        fwrite($file, $out);

        if (fclose($file)) {
            return $file_name;
        }
        return false;
    }

    /**
     * Converts an array to a CSV string.
     * @param array $array
     * @return bool|string
     */
    protected function arrayToCSV(array $array)
    {
        if (empty($array)) {
            return false;
        }

        $out = "";
        foreach ($array as $iterator => $arr) {
            foreach ($arr as $key => $value) {
                // check if we have set up to use only specific columns and use them
                if (!empty($this->fields) && !in_array($key, $this->fields)) {
                    unset($array[$iterator][$key]);
                    continue;
                }
                if (!is_array($value)) {
                    $out .= $value . ",";
                }
            }
            $out = trim($out, ',') . PHP_EOL;

            // add the row of keys
            if (key($this->data) === $iterator) {
                $out = implode(",", array_keys($array[$iterator])) . PHP_EOL . $out;
            }
        }
        return $out;
    }

    private function validateCurrentPath()
    {
        return file_exists($this->path);
    }
}