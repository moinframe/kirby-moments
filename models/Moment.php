<?php

declare(strict_types=1);

class MomentPage extends Kirby\Cms\Page
{
	public function url($options = null): string
	{
		$parent = site()->getMomentsPage();
		if (!$parent) {
			return '';
		}
		return $parent->url() . '/' . $this->slug();
	}

	public function image(string|null $filename = null): Kirby\Cms\File|null
	{
		if (!$filename) {
			return $this->parent()->images()->template('moment')->findBy('name', $this->slug());
		}

		return parent::image($filename);
	}

	public function toMomentsInterface(): array
	{
		$allMoments = collection('moments/all')->flip();
		$image = $this->image();
		$lightboxCrop = $image?->crop(900);

		return [
			'url' => $this->url(),
			'image' => $image ? [
				'src' => $lightboxCrop?->url(),
				'srcset' => $image->srcset(option('moinframe.moments.thumbs.srcsets.lightbox')),
				'webpSrcset' => option('moinframe.moments.thumbs.srcsets.lightbox-webp')
					? $image->srcset(option('moinframe.moments.thumbs.srcsets.lightbox-webp'))
					: null,
				'alt' => $this->alt()->or($this->text())->or($this->title())->escape()->value(),
				'width' => $lightboxCrop?->width(),
				'height' => $lightboxCrop?->height(),
				'sizes' => option('moinframe.moments.thumbs.sizes.lightbox', '100vw'),
			] : null,
			'text' => $this->text()->isNotEmpty() ? $this->text()->escape()->value() : null,
			'date' => $this->date()->isNotEmpty() ? [
				'timestamp' => $this->date()->toMomentsTimestamp(),
				'formatted' => $this->date()->toMomentsDate(),
			] : null,
			'prev' => $this->hasPrev($allMoments) ? $this->prev($allMoments)->url() : null,
			'next' => $this->hasNext($allMoments) ? $this->next($allMoments)->url() : null,
		];
	}
}
