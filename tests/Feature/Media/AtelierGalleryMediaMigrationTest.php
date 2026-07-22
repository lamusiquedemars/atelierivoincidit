<?php

namespace Tests\Feature\Media;

use App\Modules\Gallery\Models\Gallery;
use App\Modules\Gallery\Models\GalleryImage;
use App\Modules\Media\Services\MediaAuditService;
use App\Modules\Media\Services\MediaMigrationPlanner;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AtelierGalleryMediaMigrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_gallery_reference_is_migrated_cleaned_and_rolled_back(): void
    {
        Storage::fake('public');
        $root = sys_get_temp_dir().'/atelier-gallery-media-'.uniqid();
        mkdir($root.'/public/galleries/atelier-home', 0777, true);
        mkdir($root.'/legacy', 0777, true);
        mkdir($root.'/private', 0777, true);
        $relativePath = 'galleries/atelier-home/photo.png';
        $source = $root.'/public/'.$relativePath;
        file_put_contents($source, base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNk+A8AAQUBAScY42YAAAAASUVORK5CYII='));
        $roots = ['public' => $root.'/public', 'legacy_public' => $root.'/legacy', 'private' => $root.'/private'];
        App::instance(MediaAuditService::class, new MediaAuditService($roots, $root.'/code'));
        $planner = new MediaMigrationPlanner($roots);
        $name = 'atelier-test-'.uniqid().'.json';

        $gallery = Gallery::query()->create(['title' => 'Atelier', 'slug' => 'atelier-test']);
        $image = GalleryImage::query()->create([
            'gallery_id' => $gallery->id,
            'title' => 'Le geste',
            'image_path' => $relativePath,
            'is_published' => true,
        ]);

        try {
            $planner->writeManifest($planner->plan(), $name);
            $applied = $planner->apply($name);
            $entry = $applied['entries'][0];

            $this->assertSame('applied', $applied['status']);
            $this->assertSame($entry['media_asset_id'], $image->refresh()->media_asset_id);
            $this->assertSame($relativePath, $image->image_path);
            $this->assertDatabaseHas('media_usages', [
                'media_asset_id' => $entry['media_asset_id'],
                'usable_type' => GalleryImage::class,
                'usable_id' => $image->id,
                'field' => 'media_asset_id',
            ]);
            $this->assertFileExists($source);

            $planner->cleanup($name);
            $this->assertFileDoesNotExist($source);

            $rolledBack = $planner->rollback($name);

            $this->assertSame('rolled_back', $rolledBack['status']);
            $this->assertNull($image->refresh()->media_asset_id);
            $this->assertSame($relativePath, $image->image_path);
            $this->assertDatabaseCount('media_assets', 0);
            $this->assertDatabaseCount('media_usages', 0);
            $this->assertFileExists($source);
        } finally {
            @unlink($planner->manifestPath($name));
            @unlink($source);
            @rmdir(dirname($source));
            @rmdir($root.'/public/galleries');
            @rmdir($root.'/public');
            @rmdir($root.'/legacy');
            @rmdir($root.'/private');
            @rmdir($root);
        }
    }

    public function test_a_tracked_gallery_asset_is_copied_but_never_deleted_by_cleanup(): void
    {
        Storage::fake('public');
        $filename = 'gallery-asset-'.uniqid().'.png';
        $source = public_path($filename);
        file_put_contents($source, base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNk+A8AAQUBAScY42YAAAAASUVORK5CYII='));
        $planner = new MediaMigrationPlanner;
        $name = 'atelier-assets-test-'.uniqid().'.json';
        $gallery = Gallery::query()->create(['title' => 'Assets', 'slug' => 'assets-test']);
        $image = GalleryImage::query()->create([
            'gallery_id' => $gallery->id,
            'title' => 'Asset suivi',
            'image_path' => '/'.$filename,
            'is_published' => true,
        ]);

        try {
            $plan = $planner->planGalleryAssets();
            $this->assertTrue($plan['entries'][0]['preserve_sources']);
            $planner->writeManifest($plan, $name);
            $applied = $planner->apply($name);

            $this->assertNotNull($image->refresh()->media_asset_id);
            Storage::disk('public')->assertExists($applied['entries'][0]['destination']);

            $planner->cleanup($name);
            $this->assertFileExists($source);

            $planner->rollback($name);
            $this->assertNull($image->refresh()->media_asset_id);
            $this->assertFileExists($source);
        } finally {
            @unlink($planner->manifestPath($name));
            @unlink($source);
        }
    }

    public function test_cleanup_can_recover_an_unreferenced_asset_created_by_its_manifest(): void
    {
        Storage::fake('public');
        $root = sys_get_temp_dir().'/atelier-media-recovery-'.uniqid();
        mkdir($root.'/public/pages', 0777, true);
        mkdir($root.'/legacy', 0777, true);
        mkdir($root.'/private', 0777, true);
        $source = $root.'/public/pages/orphan.png';
        file_put_contents($source, base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNk+A8AAQUBAScY42YAAAAASUVORK5CYII='));
        $roots = ['public' => $root.'/public', 'legacy_public' => $root.'/legacy', 'private' => $root.'/private'];
        App::instance(MediaAuditService::class, new MediaAuditService($roots, $root.'/code'));
        $planner = new MediaMigrationPlanner($roots);
        $name = 'atelier-recovery-test-'.uniqid().'.json';

        try {
            $planner->writeManifest($planner->plan(), $name);
            $applied = $planner->apply($name);
            $entry = $applied['entries'][0];
            Storage::disk('public')->delete($entry['destination']);
            DB::table('media_assets')->where('id', $entry['media_asset_id'])->delete();

            $cleaned = $planner->cleanup($name);

            $this->assertSame('cleaned', $cleaned['status']);
            $this->assertDatabaseHas('media_assets', ['id' => $entry['media_asset_id']]);
            Storage::disk('public')->assertExists($entry['destination']);
            $this->assertFileDoesNotExist($source);
        } finally {
            @unlink($planner->manifestPath($name));
            @unlink($source);
            @rmdir($root.'/public/pages');
            @rmdir($root.'/public');
            @rmdir($root.'/legacy');
            @rmdir($root.'/private');
            @rmdir($root);
        }
    }
}
