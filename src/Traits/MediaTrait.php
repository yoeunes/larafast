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

    public $imageQuality = 100;

    /**
     * @param string $thumb
     *
     * @return string
     */
    public function image($thumb = ''): string
    {
        return $this->getMedia()->count() ? $this->getMedia()->first()->getUrl($thumb) : self::DEFAULT_IMAGE;
    }

    /**
     * @return string
     */
    public function imageName(): string
    {
        return $this->getMedia()->count() ? $this->getMedia()->first()->name : self::DEFAULT_IMAGE;
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
