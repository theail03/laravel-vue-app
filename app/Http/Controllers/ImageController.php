<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\Matrix;
use App\Helpers\UserHelper;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\ImageResource;
use App\Http\Requests\SaveImageRequest;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class ImageController extends Controller
{
    // Retrieve all images for a specific matrix
    public function getImages(Matrix $matrix, Request $request)
    {
        if (!$matrix->is_public) {
            UserHelper::authorizeUser($matrix->user_id);
        }

        $images = $matrix->images;
        return ImageResource::collection($images);
    }

    // Retrieve a specific image
    public function getImage(Matrix $matrix, Request $request, $row, $column)
    {
        if (!$matrix->is_public) {
            UserHelper::authorizeUser($matrix->user_id);
        }

        // Use the relationship to find the specific image
        $image = $matrix->images()
                        ->where('row', $row)
                        ->where('column', $column)
                        ->firstOrFail();
        return new ImageResource($image);
    }

    public function saveImage(Matrix $matrix, SaveImageRequest $request, $row, $column)
    {
        UserHelper::authorizeUser($matrix->user_id);

        $imageData = $request->input('data'); // This is the base64-encoded image data.

        // Check if imageData is a valid base64 image string
        if (!preg_match('/^data:image\/(\w+);base64,/', $imageData, $typeMatch)) {
            throw new \Exception('Invalid image data');
        }

        $type = strtolower($typeMatch[1]);
        if (!in_array($type, ['jpg', 'jpeg', 'gif', 'png'])) {
            throw new \Exception('Invalid image type');
        }

        // Upload the image to Cloudinary
        $cloudinaryResponse = Cloudinary::upload($imageData, [
            'folder' => 'matrix_images',
            'public_id' => Str::random(10),
            'resource_type' => 'image'
        ]);

        // Get the public ID from the Cloudinary response
        $publicId = $cloudinaryResponse->getPublicId();

        // Get the secure URL from the Cloudinary response
        $secureUrl = $cloudinaryResponse->getSecurePath();

        // Now, create or update the Image model with the secure URL
        $imageModel = Image::updateOrCreate(
            [
                'matrix_id' => $matrix->id,
                'row' => $row,
                'column' => $column,
            ],
            [
                'user_id' => Auth::id(),
                'path' => $secureUrl, // Store the secure URL from Cloudinary
                'public_id' => $publicId // Also store the public ID
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

        if ($image->public_id) {
            // Delete the image from Cloudinary
            Cloudinary::destroy($image->public_id);
        }

        $image->delete();

        // Return a 204 No Content response
        return response()->noContent();
    }
}
