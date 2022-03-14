<?php
/**
 * Created by PhpStorm.
 * User: Hungokata
 * Date: 2020/08/15 - 17:00
 */

namespace Workable\FileUploader\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Workable\FileUploader\Services\ImageUploaderService;

class ImageUploadApiController extends Controller
{
    // Upload
    public function uploadImage()
    {
        $result = ImageUploaderService::upload('file', 'logo', 'resize');
        return $result ? response()->json($result->toArray()) : response()->json(null);
    }

    // Upload multi
    public function uploadImageMulti(Request $request)
    {
        $results = ImageUploaderService::uploadMulti('files', 'logo');
        foreach ($results as &$result) {
            $result = $result->toArray();
        }
        return $results ? response()->json($results) : response()->json(null);
    }

    // Upload base 64
    public function uploadFromBase64(Request $request)
    {
        $base64 = $request->base64;
        $results = ImageUploaderService::uploadFromBase64($base64, 'logo');
        return $results ? response()->json($results->toArray()) : response()->json(null);
    }

    // Upload link
    public function uploadFromLink(Request $request)
    {
        $link = $request->get('link');
        $result = ImageUploaderService::uploadFromLink($link, 'logo');
        return $result ? response()->json($result->toArray()) : response()->json(null);
    }

    // Delete
    public function deleteImage(Request $request)
    {
        $fileName = $request->file_name;
        $results = ImageUploaderService::deleteImage($fileName, 'logo');

        return response()->json($results);
    }
}
