<?php

namespace App\Http\Controllers;

use App\Services\DigitalPassService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

class DigitalPassController extends Controller
{
    public function show(Request $request, string $pass, DigitalPassService $digitalPassService): View|Response
    {
        $passData = $digitalPassService->buildFromPassCode($pass);

        if ($passData === null) {
            abort(404, 'Event pass not found.');
        }

        return view('digital-pass.show', [
            'passData' => $passData,
            'qrContent' => $pass,
        ]);
    }

    public function download(Request $request, string $pass, DigitalPassService $digitalPassService): View|Response
    {
        $passData = $digitalPassService->buildFromPassCode($pass);

        if ($passData === null) {
            abort(404, 'Event pass not found.');
        }

        $filename = 'EMS-Event-Pass-'.preg_replace('/[^A-Za-z0-9\-]+/', '-', $pass).'.html';

        return response()
            ->view('digital-pass.download', [
                'passData' => $passData,
                'qrContent' => $pass,
            ])
            ->header('Content-Type', 'text/html; charset=UTF-8')
            ->header('Content-Disposition', 'attachment; filename="'.$filename.'"');
    }
}
