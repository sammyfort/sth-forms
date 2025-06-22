<?php

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

if (!function_exists("successRes")) {
    function successRes(string $message): array{
        return [
            'success' => true,
            'message' => $message
        ];
    }
}

if (!function_exists("errorRes")) {
    function errorRes(string $message="Something went wrong"): array{
        return [
            'success' => false,
            'message' => $message
        ];
    }
}

if (!function_exists('dateFormat')){
    function dateFormat($time = false): string
    {
        if ($time) return 'D, jS M Y h:i A';
        return 'D, jS M Y';
    }
}

if (!function_exists('uploadToGallery')){
    function uploadToGallery(Model $model, $image, $collection): Media
    {
        if (!in_array(InteractsWithMedia::class, class_uses_recursive($model))){
            throw new Exception("this model type is not a mediable class");
        }
        return $model->addMedia($image)
            ->toMediaCollection($collection);
    }
}
