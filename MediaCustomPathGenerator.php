<?php

namespace App\Services;

use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\Support\PathGenerator\PathGenerator;

class MediaCustomPathGenerator implements PathGenerator
{
    public function getPath(Media $media): string
    {
        // return str($media->model_type)->afterLast('\\')->plural()->__toString() . '/' . $media->model?->id . '/';
        return  str($media->model_type)->afterLast('\\')->plural()->__toString() . '/'.($media->id). '/';
    }

    public function getPathForConversions(Media $media): string
    {
        // return $this->getPath($media) . 'conversions/';
           return  str($media->model_type)->afterLast('\\')->plural()->__toString() . '/'.($media->id). '/conversions/';
    }

    public function getPathForResponsiveImages(Media $media): string
    {
        // return $this->getPath($media) . 'responsive/';
           return  str($media->model_type)->afterLast('\\')->plural()->__toString() . '/'.($media->id). '/responsive/';
    }
}




/** 
 OLD PATH GENERATOR
 */
// namespace App\Services;

// use Spatie\MediaLibrary\MediaCollections\Models\Media;
// use Spatie\MediaLibrary\Support\PathGenerator\PathGenerator;

// class MediaCustomPathGenerator implements PathGenerator
// {
//     public function getPath(Media $media): string
//     {
//         return str($media->model_type)->afterLast('\\')->plural()->__toString() . '/' . $media->model?->id . '/';
//     }

//     public function getPathForConversions(Media $media): string
//     {
//         return $this->getPath($media) . 'conversions/';
//     }

//     public function getPathForResponsiveImages(Media $media): string
//     {
//         return $this->getPath($media) . 'responsive/';
//     }
// }
