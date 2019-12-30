<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Services\S3Service;
use Illuminate\Http\UploadedFile;
use App\Http\Requests\S3UploadRequest;

class AwsController extends Controller
{
    public function __invoke(S3UploadRequest $request, S3Service $s3)
    {
        /**
         * @var UploadedFile $file
         */
        $file = $request->file('file');
        $fileName = Carbon::now()->timestamp
            . '_' . $request->input('name')
            . '.' . $file->getClientOriginalExtension();
        $s3->upload($file, $fileName);
        $s3Response = $s3->getLastResponse();
        $status = Response::HTTP_OK;
        if ($s3Response === null) {
            $response = [
                'errors' => ['aws' => $s3->getLastError()]
            ];
            $status = Response::HTTP_UNPROCESSABLE_ENTITY;
        } else {
            $response = [
                'data' => $s3->getLastResponse()->toArray(),
            ];
        }
        return response()->json($response, $status);
    }
}
