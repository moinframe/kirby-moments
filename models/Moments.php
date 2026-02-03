<?php

declare(strict_types=1);

class MomentsPage extends Kirby\Cms\Page
{
    public function children(): Kirby\Cms\Pages
    {
        $images = [];

        foreach ($this->images()->template('moment') as $image) {
            $images[] = [
                'slug'     => $image->name(),
                'template' => 'moment',
                'model'    => 'moment',
                'content'  => $image->content()->toArray()
            ];
        }

        return Kirby\Cms\Pages::factory($images, $this);
    }
}
