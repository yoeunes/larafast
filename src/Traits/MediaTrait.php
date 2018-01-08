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
    protected $thumbSize = [
        'width'  => 100,
        'height' => 100,
    ];

    public $clearMediaCollection = true;

    public $showImageInDataTable = true;

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

    /**
     * @param Media|null $media
     */
    public function registerMediaConversions(Media $media = null)
    {
        try {
            $this->addMediaConversion('thumb')
                ->width($this->thumbSize['width'])
                ->height($this->thumbSize['height'])
                ->quality(20)
                ->optimize();
        } catch (InvalidManipulation $e) {
        }
    }
}
