<?php

namespace Peresmishnyk\Task\Services;

class PhoneParser
{
    protected $data = [];
    protected $max_lenght = 0;

    public function __construct(string $datasource)
    {
        // Init parser
        if (($handle = fopen($datasource, "r")) !== false) {
            while (($data = fgetcsv($handle, 1000, "\t")) !== false) {
                if (!str_starts_with($data[0], "#")) {
                    // Remove non digit
                    $code = (string)preg_replace("/\D/", '', $data[12]);
                    $continent = trim($data[8]);
                    $this->data[$code] = $continent;
                    $this->max_lenght = max($this->max_lenght, strlen($code));
                }
            }
            fclose($handle);
        }
    }

    public function getContinentByPhone($phone): string
    {
        // Remove non digit
        $phone = (string)preg_replace("/\D/", '', $phone);
        $phone_len = strlen($phone);
        $continent = null;
        $len = 0;
        do {
            $code = substr($phone, 0, ++$len);
            $continent = $this->data[$code] ?? null;
        } while (is_null($continent) && $len < $this->max_lenght && $len < $phone_len);
        return $continent;
    }
}