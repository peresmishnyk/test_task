<?php

namespace Peresmishnyk\Task\Http\Controllers;

use Symfony\Component\HttpFoundation\Request;

class SubmitController extends BaseController
{
    protected static function upload(Request $request, array $params)
    {
        $uuid = uuid_v4();
        $files = $request->files;
        $path = app()->service('filestorage')->getStoragePath();

        if ($files->has('file')) {
            $file = $files->get('file');
            if ($file){
                $file->move($path, $uuid);
            } else {
                return abort(500, 'No file submmited');
            }
            static::runParserWorker($uuid);
            return static::redirectToShow($request, $uuid);
        } else {
            return abort(500);
        }
    }

    protected static function download(Request $request, array $params)
    {
        $uuid = uuid_v4();
        $url = $request->get('url');

        if (!$url){
            return abort(500, 'No url submmited');
        }

        if ($uuid !== false) {
            static::runDownloadWorker($url, $uuid);
            return static::redirectToShow($request, $uuid);
        } else {
            return abort(500);
        }
    }

    protected static function plain(Request $request, array $params)
    {
        $uuid = uuid_v4();
        $data = $request->get('plain');

        if ($data != '') {
            app()->service('filestorage')->putFileContent($uuid, $data);
            static::runParserWorker($uuid);
            return static::redirectToShow($request, $uuid);
        } else {
            return abort(500, 'No data submmited');
        }
    }

    protected static function redirectToShow(Request $request, $uuid)
    {
        $shemeAndHost = $request->getSchemeAndHttpHost();
        $path = route('show', ['uuid' => $uuid]);
        return redirect($shemeAndHost . $path);
    }

    protected static function runParserWorker($uuid)
    {
        shell_exec(app_path('..') . DIRECTORY_SEPARATOR . '/task -c parse -u "' . $uuid . '" >/dev/null 2>/dev/null &');
    }

    protected static function runDownloadWorker($source, $uuid)
    {
        app()->service('filestorage')->touch($uuid);
        shell_exec(app_path('..') . DIRECTORY_SEPARATOR . '/task -c download -s "' . $source . '" -u "' . $uuid . '">/dev/null 2>/dev/null &');
    }

}