<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Casts\Attribute;
trait HasMediaUploads
{

    public function handleUploads(Request $request, array $mediaFields): void
    {
        foreach ($mediaFields as $field => $collection) {
            if (!$request->hasFile($field)) {
                continue;
            }

            $files = $request->file($field);

            if (is_array($files)) {
                foreach ($files as $file) {
                    $this->addMedia($file)->toMediaCollection($collection);
                }
            } else {
                $this->addMediaFromRequest($field)->toMediaCollection($collection);
            }
        }
    }

    public function handleMediaUpdate(Request $request, array $options = []): void
    {
        $featuredKey = $options['featured'] ?? 'featured';
        $galleryKey  = $options['gallery'] ?? 'gallery';
        $removedKey  = $options['removed'] ?? 'removed_gallery_urls';

        if ($request->hasFile($featuredKey)) {
            $this->addMediaFromRequest($featuredKey)->toMediaCollection('featured');
        }

        $removedUrls = $request->input($removedKey, []);
        if (!empty($removedUrls)) {
            foreach ($this->getMedia($galleryKey) as $media) {
                if (in_array($media->getUrl(), $removedUrls)) {
                    $media->delete();
                }
            }
        }

        if ($request->hasFile($galleryKey)) {
            foreach ((array) $request->file($galleryKey) as $file) {
                $this->addMedia($file)->toMediaCollection($galleryKey);
            }
        }
    }
    public function toArrayWithMedia(): array
    {
        $data = $this->toArray();
        $featuredMedia = $this->getFirstMedia('featured');
        $data['featured'] = $featuredMedia ? $featuredMedia->getUrl() : null;

        $galleryMedia = $this->getMedia('gallery');
        $data['gallery'] = $galleryMedia->map(fn($media) => $media->getUrl())->toArray();

        return $data;
    }

    protected function featured(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->getFirstMediaUrl('featured') ?: null,
        );
    }

    protected function gallery(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->getMedia('gallery')->map(fn($media) => $media->getUrl())->toArray(),
        );
    }
}
