<?php

namespace Yoeunes\Larafast\Traits;

use Spatie\MediaLibrary\Media;
use Illuminate\Support\Collection;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\Image\Exceptions\InvalidManipulation;

trait MediaTrait
{
    use HasMediaTrait;

    /** @var array */
    public $imageMeta = [];

    public $clearMediaCollection = true;

    public $showImageInDataTable = true;

    public $imageToShowInDataTable = 'thumb';

    /**
     * @param string $thumb
     * @param string $default_image
     *
     * @return string
     */
    public function image($thumb = '', $default_image = 'https://placehold.it/100x100'): string
    {
        return $this->getMedia()->count() ? $this->getMedia()->first()->getUrl($thumb) : $default_image;
    }

    /**
     * @param string $default_image_name
     *
     * @return string
     */
    public function imageName($default_image_name = ''): string
    {
        return $this->getMedia()->count() ? $this->getMedia()->first()->name : $default_image_name;
    }

    /**
     * @return Collection
     */
    public function images(): Collection
    {
        return $this->getMedia();
    }

    public function getImageMeta()
    {
        return array_merge([
            'width'   => 100,
            'height'  => 100,
            'quality' => 90,
            'lazy'    => [
                'width'   => 100,
                'height'  => 100,
                'quality' => 90,
                'blur'    => 80,
            ],
        ], $this->imageMeta);
    }

    /**
     * @param Media|null $media
     */
    public function registerMediaConversions(Media $media = null)
    {
        try {
            $this->addMediaConversion('thumb')
                ->width($this->getImageMeta()['width'] ?? 100)
                ->height($this->getImageMeta()['height'] ?? 100)
                ->quality($this->getImageMeta()['quality'] ?? 90)
                ->optimize();

            $this->addMediaConversion('lazy')
                ->width($this->getImageMeta()['lazy']['width'] ?? 100)
                ->height($this->getImageMeta()['lazy']['height'] ?? 100)
                ->quality($this->getImageMeta()['lazy']['quality'] ?? 90)
                ->blur($this->getImageMeta()['lazy']['blur'] ?? 80)
                ->optimize();
        } catch (InvalidManipulation $e) {
        }
    }
}
