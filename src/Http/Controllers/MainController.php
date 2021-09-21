<?php

namespace Peresmishnyk\Task\Http\Controllers;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MainController extends BaseController
{
    protected static function index()
    {
        app()->service('data');
        return view('index.twig');
    }

    protected static function demo(Request $request, $params)
    {
        $content = file_get_contents(config('app')['storage_dir'] . DIRECTORY_SEPARATOR . 'cdrs.csv');
        $headers = [
            'Content-type' => 'application/octect-stream',
            'Content-Disposition' => 'attachment; filename="cdrs.csv"',
            'Content-Length' => strlen($content),
            'Content-Transfer-Encoding' => 'binary',
        ];
        return new Response($content, 200, $headers);
    }

    protected static function all(Request $request, $params)
    {
        $uuids = app()->service('data')->getReports();
        return view('all.twig', [
            'uuids' => $uuids
        ]);
    }

    protected static function show(Request $request, $params)
    {
        $uuid = $params['uuid'];
        $status = app()->service('data')->getReady($uuid);

        if (!app()->service('filestorage')->checkFileExists($uuid)) {
            return abort(404, 'UUID not Found!');
        }

        if ($status == 1) {
            return view('show.twig', [
                'uuid' => $uuid,
                'permalink' => $request->getSchemeAndHttpHost() . route('show', ['uuid' => $uuid]),
                'report' => app()->service('data')->getReport($uuid)
            ]);
        } else {
            return view('wait.twig', [
                'uuid' => $uuid,
                'percentage' => floor($status * 100)
            ]);
        }
    }
}