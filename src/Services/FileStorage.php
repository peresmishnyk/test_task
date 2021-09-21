<?php

namespace Peresmishnyk\Task\Services;

class FileStorage
{
    protected $path;

    public function __construct($path)
    {
        $this->path = $path;
        @mkdir($this->path, 0777, true);
    }

    public function getStoragePath()
    {
        return $this->path;
    }

    public function getFilePath($uuid)
    {
        return $this->path . DIRECTORY_SEPARATOR . $uuid;
    }

    public function checkFileExists($uuid)
    {
        return file_exists($this->getFilePath($uuid));
    }

    public function getFileContent($uuid)
    {
        return file_get_contents($this->getFilePath($uuid));
    }

    public function putFileContent($uuid, $data)
    {
        return file_put_contents($this->getFilePath($uuid), $data);
    }

    public function unlink($uuid)
    {
        return unlink($this->getFilePath($uuid));
    }

    public function touch($uuid)
    {
        return touch($this->getFilePath($uuid));
    }
}