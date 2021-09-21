<?php

namespace Peresmishnyk\Task\Console;

class ConsoleCommand
{
    public static function download($source, $uuid)
    {
        $content = @file_get_contents($source);

        if ($content !== false) {
            app()->service('filestorage')->putFileContent($uuid, $content);
            return $uuid;
        }
        return false;
    }

    public static function parse($uuid)
    {
        $parser = app()->service('parser');
        $filepath = app()->service('filestorage')->getFilePath($uuid);
        $parser->parse($filepath);
        return $uuid;
    }

    public static function report($uuid)
    {
        $report = app()->service('data')->getReport($uuid);
        return $report;
    }
}