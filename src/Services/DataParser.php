<?php

namespace Peresmishnyk\Task\Services;

class DataParser
{
    protected $phoneParser;
    protected $ipParser;
    protected $dataStorage;

    public function __construct(PhoneParser $phoneParser, IpParser $ipParser, DataStorage $dataStorage)
    {
        $this->phoneParser = $phoneParser;
        $this->ipParser = $ipParser;
        $this->dataStorage = $dataStorage;
    }

    public function parse($file_path)
    {
        $uuid = basename($file_path);
        $this->dataStorage->clear($uuid);
        if (($handle = fopen($file_path, "r")) !== false) {
            while (($data = fgetcsv($handle, 1000, ",")) !== false) {
                list($customerId, $datetime, $duration, $phone, $ip) = $data;
                $this->dataStorage->addRow($uuid, $customerId, $datetime, $duration, $phone, $ip);
            }
            fclose($handle);
            $this->updateContinets();
        }
    }

    public function updateContinets()
    {
        $res = true;
        foreach ($this->dataStorage->getUncompleted() as $row) {
            $continetPhone = $this->phoneParser->getContinentByPhone($row['phone']);
            $continetIp = $this->ipParser->getContinentByIp($row['ip']);
            $res = $res & $this->dataStorage->updateContinents($row['rowid'], $continetPhone, $continetIp);
        };
        return $res;

    }
}