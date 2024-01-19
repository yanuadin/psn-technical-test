<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use File;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function jsonResponse(bool $isSuccess, mixed $data, int $status = 200, string $message = 'SUCCESS'): JsonResponse
    {
        return new JsonResponse([
            'is_success' => $isSuccess,
            'message' => $message,
            'data' => $data,
        ], $status);
    }

    protected function storeFile(Request $request, string $keyword, string $path, string|null $file_name = null): string | null
    {
        $image_path = null;
        if($request[$keyword] !== null) {
            $uploadFile = $request->file($keyword);
            if(empty($file_name))
                $image_path = $uploadFile?->store($path, ['disk' => 'public']);
            else
                $image_path = $uploadFile?->storeAs($path, $file_name . '.' . $uploadFile->getClientOriginalExtension(), ['disk' => 'public']);
        }

        return $image_path;
    }

    protected function updateFile(Request $request, string $keyword, string $path, mixed $model, string $column, string|null $file_name = null): string | null
    {
        $image_path = null;
        if(!empty($request[$keyword]) || !empty($model->getAttribute($column))) {
            $uploadFile = $request->file($keyword);

            if($uploadFile !== null){
                $this->deleteFile($model, $column);
                $image_path = $this->storeFile($request, $keyword, $path, $file_name);
            } else {
                $image_path = $model->getAttribute($column);
            }
        }

        return $image_path;
    }

    protected function deleteFile(mixed $model, string $column): void
    {
        File::delete(storage_path('app/public/') . $model->getAttribute($column));
    }
}
