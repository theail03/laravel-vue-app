<?php

namespace App\Http\Controllers;

use App\Http\Resources\MatrixResource;
use App\Models\Matrix;
use App\Http\Requests\StoreMatrixRequest;
use App\Http\Requests\UpdateMatrixRequest;
use App\Helpers\UserHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class MatrixController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = $request->user();

        return MatrixResource::collection(Matrix::where('user_id', $user->id)->orderBy('created_at', 'DESC')->paginate(10));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\StoreMatrixRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreMatrixRequest $request)
    {
        $data = $request->validated();

        $matrix = Matrix::create($data);

        return new MatrixResource($matrix);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Matrix $survey
     * @return \Illuminate\Http\Response
     */
    public function show(Matrix $matrix, Request $request)
    {
        UserHelper::authorizeUser($matrix->user_id);

        return new MatrixResource($matrix);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\UpdateMatrixRequest $request
     * @param \App\Models\Matrix                     $matrix
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateMatrixRequest $request, Matrix $matrix)
    {
        $data = $request->validated();

        // Update matrix in the database
        $matrix->update($data);

        return new MatrixResource($matrix);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Matrix $matrix
     * @return \Illuminate\Http\Response
     */
    public function destroy(Matrix $matrix, Request $request)
    {
        UserHelper::authorizeUser($matrix->user_id);
    
        // Retrieve all images associated with the matrix
        $images = $matrix->images;
    
        // Begin transaction to ensure data integrity
        DB::transaction(function () use ($matrix, $images) {
            // Loop over each image and delete the associated file
            foreach ($images as $image) {
                $absolutePath = public_path($image->path);
                if (File::exists($absolutePath)) {
                    File::delete($absolutePath); // Delete the file from the server
                }
            }
    
            // After all files are deleted, delete the image records
            $matrix->images()->delete();
    
            // Finally, delete the matrix record
            $matrix->delete();
        });
    
        // Return a 204 No Content response
        return response()->noContent();
    }
}
