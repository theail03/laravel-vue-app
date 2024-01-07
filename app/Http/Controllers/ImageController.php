<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\Matrix;
use App\Helpers\UserHelper;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use App\Http\Resources\ImageResource;
use App\Http\Requests\SaveImageRequest;

class ImageController extends Controller
{
    // Retrieve all images for a specific matrix
    public function getImages(Matrix $matrix, Request $request)
    {
        UserHelper::authorizeUser($matrix->user_id);

        $images = $matrix->images;
        return ImageResource::collection($images);
    }

    // Retrieve a specific image
    public function getImage(Matrix $matrix, Request $request, $row, $column)
    {
        UserHelper::authorizeUser($matrix->user_id);

        // Use the relationship to find the specific image
        $image = $matrix->images()
                        ->where('row', $row)
                        ->where('column', $column)
                        ->firstOrFail();
        return new ImageResource($image);
    }

    // Save (create or update) a specific image
    public function saveImage(Matrix $matrix, SaveImageRequest $request, $row, $column)
    {
        UserHelper::authorizeUser($matrix->user_id);

        $image = $request->input('data'); // This is the base64-encoded image data.

        // Check if image is valid base64 string
        if (preg_match('/^data:image\/(\w+);base64,/', $image, $type)) {
            // Take out the base64 encoded text without mime type
            $image = substr($image, strpos($image, ',') + 1);
            // Get file extension
            $type = strtolower($type[1]); // jpg, png, gif

            // Check if file is an image
            if (!in_array($type, ['jpg', 'jpeg', 'gif', 'png'])) {
                throw new \Exception('invalid image type');
            }
            $image = str_replace(' ', '+', $image);
            $image = base64_decode($image);

            if ($image === false) {
                throw new \Exception('base64_decode failed');
            }
        } else {
            throw new \Exception('did not match data URI with image data');
        }

        $dir = 'images/';
        $file = Str::random() . '.' . $type;
        $absolutePath = public_path($dir);
        $relativePath = $dir . $file;
        if (!File::exists($absolutePath)) {
            File::makeDirectory($absolutePath, 0755, true);
        }
        file_put_contents($relativePath, $image);

        // Now, create or update the Image model
        $imageModel = Image::updateOrCreate(
            [
                'matrix_id' => $matrix->id,
                'row' => $row,
                'column' => $column,
            ],
            [
                'user_id' => Auth::id(),
                'path' => $relativePath // This is the path where the image is saved
            ]
        );

        // Return the Image model or resource
        return new ImageResource($imageModel);
    }

    // Delete a specific image
    public function deleteImage(Matrix $matrix, Request $request, $row, $column)
    {
        UserHelper::authorizeUser($matrix->user_id);

        // Use the relationship to find the specific image
        $image = $matrix->images()
                        ->where('row', $row)
                        ->where('column', $column)
                        ->firstOrFail();

        $absolutePath = public_path($image->path);
        File::delete($absolutePath);

        $image->delete();

        // Return a 204 No Content response
        return response()->noContent();
    }
}
