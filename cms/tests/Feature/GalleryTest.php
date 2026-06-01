<?php

namespace Tests\Feature;

use App\Modules\Gallery\Models\Gallery;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GalleryTest extends TestCase
{
    use RefreshDatabase;

    public function test_gallery_slug_is_generated_from_title(): void
    {
        $gallery = Gallery::query()->create([
            'title' => 'Galerie atelier',
            'is_published' => true,
        ]);

        $this->assertSame('galerie-atelier', $gallery->slug);
    }

    public function test_gallery_slug_is_unique_when_generated(): void
    {
        Gallery::query()->create([
            'title' => 'Galerie atelier',
            'is_published' => true,
        ]);

        $gallery = Gallery::query()->create([
            'title' => 'Galerie atelier',
            'is_published' => true,
        ]);

        $this->assertSame('galerie-atelier-2', $gallery->slug);
    }

    public function test_front_galleries_are_system_galleries(): void
    {
        $gallery = Gallery::query()->create([
            'title' => 'Galerie d’atelier',
            'slug' => 'atelier-home',
            'is_published' => true,
        ]);

        $this->assertTrue($gallery->isSystemGallery());
    }
}
