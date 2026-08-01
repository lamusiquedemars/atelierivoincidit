<?php

namespace Tests\Feature;

use App\Filament\Resources\Arcus\Bows\Pages\ManageBows;
use App\Models\User;
use App\Modules\Arcus\Models\ArcusBowMedia;
use App\Modules\Arcus\Models\ArcusTerm;
use App\Modules\Arcus\Models\Bow;
use App\Modules\Arcus\Support\ArcusCatalog;
use App\Modules\Media\Models\MediaAsset;
use App\Modules\Media\Models\MediaUsage;
use Filament\Actions\Testing\TestAction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ArcusNativeTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    public function test_the_first_ordered_photo_is_used_on_the_bow_card_and_gallery(): void
    {
        $bow = $this->createBow();
        $second = $this->createMedia('second.jpg');
        $first = $this->createMedia('first.jpg');

        $bow->images()->create(['media_asset_id' => $second->id, 'position' => 1]);
        $bow->images()->create(['media_asset_id' => $first->id, 'position' => 0]);

        $card = ArcusCatalog::bowsByRange('ars-antiqua')->first();
        $gallery = ArcusCatalog::galleryImages($bow->code);

        $this->assertSame($first->url(), $card['image']);
        $this->assertSame($first->url(), $gallery->first()->image_path);
        $this->assertSame($second->url(), $gallery->last()->image_path);
    }

    public function test_bow_photos_are_registered_as_media_usages(): void
    {
        $bow = $this->createBow();
        $media = $this->createMedia('bow.jpg');

        $image = $bow->images()->create(['media_asset_id' => $media->id, 'position' => 0]);

        $this->assertDatabaseHas('media_usages', [
            'media_asset_id' => $media->id,
            'usable_type' => ArcusBowMedia::class,
            'usable_id' => $image->id,
            'field' => 'gallery',
        ]);
        $this->assertFalse($media->fresh()->canBeDeleted());

        $image->delete();

        $this->assertSame(0, MediaUsage::query()->where('media_asset_id', $media->id)->count());
    }

    public function test_the_native_arcus_admin_page_renders(): void
    {
        $this->createBow();
        $admin = User::factory()->create(['is_admin' => true]);

        $this->actingAs($admin)
            ->get(route('filament.admin.resources.arcus.bows.arcus-bows.index'))
            ->assertOk()
            ->assertSee('Archets');
    }

    public function test_admin_can_create_a_bow_with_several_ordered_photos(): void
    {
        $range = ArcusTerm::query()->create([
            'type' => 'range',
            'name' => 'Ars Nova',
            'slug' => 'ars-nova',
        ]);
        $first = $this->createMedia('first-admin.jpg');
        $second = $this->createMedia('second-admin.jpg');
        $admin = User::factory()->create(['is_admin' => true]);

        $this->actingAs($admin);

        Livewire::test(ManageBows::class)
            ->callAction('create', data: [
                'code' => 'v-admin',
                'name' => 'Créé dans Filament',
                'status' => 'available',
                'active' => true,
                'range_id' => $range->id,
                'images' => [
                    ['media_asset_id' => $first->id, 'caption' => 'Première'],
                    ['media_asset_id' => $second->id, 'caption' => 'Seconde'],
                ],
            ])
            ->assertHasNoActionErrors();

        $bow = Bow::query()->where('code', 'v-admin')->firstOrFail();

        $this->assertSame([$first->id, $second->id], $bow->images()->pluck('media_asset_id')->all());
        $this->assertSame($first->url(), $bow->main_image_url);
    }

    public function test_admin_can_edit_a_bow_without_losing_its_photos(): void
    {
        $bow = $this->createBow();
        $first = $this->createMedia('first-edit.jpg');
        $second = $this->createMedia('second-edit.jpg');
        $firstImage = $bow->images()->create(['media_asset_id' => $first->id, 'position' => 0]);
        $secondImage = $bow->images()->create(['media_asset_id' => $second->id, 'position' => 1]);
        $admin = User::factory()->create(['is_admin' => true]);

        $this->actingAs($admin);

        Livewire::test(ManageBows::class)
            ->callAction(TestAction::make('edit')->table($bow), data: [
                'code' => $bow->code,
                'name' => $bow->name,
                'status' => 'reserved',
                'active' => true,
                'range_id' => $bow->range_id,
                'instrument_id' => $bow->instrument_id,
                'images' => [
                    'record-'.$firstImage->id => ['media_asset_id' => $first->id, 'caption' => 'Première retouchée'],
                    'record-'.$secondImage->id => ['media_asset_id' => $second->id, 'caption' => 'Seconde retouchée'],
                ],
            ])
            ->assertHasNoActionErrors();

        $bow->refresh();

        $this->assertSame('reserved', $bow->status);
        $this->assertSame([$first->id, $second->id], $bow->images()->pluck('media_asset_id')->all());
        $this->assertSame(['Première retouchée', 'Seconde retouchée'], $bow->images()->pluck('caption')->all());
        $this->assertSame($first->url(), $bow->main_image_url);
    }

    private function createBow(): Bow
    {
        $range = ArcusTerm::query()->create([
            'type' => 'range',
            'name' => 'Ars Antiqua',
            'slug' => 'ars-antiqua',
        ]);
        $instrument = ArcusTerm::query()->create([
            'type' => 'instrument',
            'name' => 'violon',
        ]);

        return Bow::query()->create([
            'code' => 'v-test',
            'name' => 'Test',
            'status' => 'available',
            'active' => true,
            'range_id' => $range->id,
            'instrument_id' => $instrument->id,
        ]);
    }

    private function createMedia(string $name): MediaAsset
    {
        return MediaAsset::query()->create([
            'type' => 'image',
            'disk' => 'public',
            'path' => 'media/images/2026/08/'.$name,
            'original_name' => $name,
            'display_name' => $name,
            'mime_type' => 'image/jpeg',
            'extension' => 'jpg',
            'size' => 100,
            'width' => 1200,
            'height' => 800,
            'alt_text' => 'Archet de test',
            'checksum' => hash('sha256', $name),
        ]);
    }
}
