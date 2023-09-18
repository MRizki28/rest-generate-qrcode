<?php

namespace App\Http\Controllers;

use App\Models\QrcodeModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\File;

class QrcodeController extends Controller
{



    public function generateQrcode(Request $request)
    {
        $url = $request->input('url');

        if (empty($url)) {
            return response()->json([
                'message' => 'URL wajib ada'
            ], 400);
        }

        // Membuat QR code
        $qrcode = QrCode::format('png')->size(200)->generate($url);

        $filename = 'qrcode_' . time() . '.png';
        $directory = 'uploads/qrcode';

        File::makeDirectory(public_path($directory), 0755, true, true);

        file_put_contents(public_path("$directory/$filename"), $qrcode);

        $qrcodeUrl = asset("$directory/$filename");

        $urlModel = new QrcodeModel();
        $urlModel->url = $url;
        $urlModel->qrcode = $filename;
        $urlModel->save();

        return response()->json([
            'message' => 'Generate QR Code berhasil',
            'data' => $qrcodeUrl
        ]);
    }
}
