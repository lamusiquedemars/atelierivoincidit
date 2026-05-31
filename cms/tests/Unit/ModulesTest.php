<?php

namespace Tests\Unit;

use App\Support\Modules;
use Tests\TestCase;

class ModulesTest extends TestCase
{
    public function test_essence_offer_disables_richer_modules(): void
    {
        config(['maracuja.offer' => 'essence']);

        $this->assertTrue(Modules::enabled('site_settings'));
        $this->assertTrue(Modules::enabled('pages'));
        $this->assertTrue(Modules::enabled('contact'));
        $this->assertFalse(Modules::enabled('notices'));
        $this->assertFalse(Modules::enabled('content_slots'));
        $this->assertFalse(Modules::enabled('news'));
        $this->assertFalse(Modules::enabled('gallery'));
    }

    public function test_module_can_still_be_disabled_inside_signature_offer(): void
    {
        config([
            'maracuja.offer' => 'signature',
            'maracuja.modules.gallery' => false,
        ]);

        $this->assertFalse(Modules::enabled('gallery'));
        $this->assertTrue(Modules::enabled('news'));
    }

    public function test_univers_offer_enables_business_ready_modules(): void
    {
        config(['maracuja.offer' => 'univers']);

        $this->assertTrue(Modules::enabled('site_settings'));
        $this->assertTrue(Modules::enabled('content_slots'));
        $this->assertTrue(Modules::enabled('pages'));
        $this->assertTrue(Modules::enabled('news'));
        $this->assertTrue(Modules::enabled('gallery'));
        $this->assertTrue(Modules::enabled('contact'));
    }
}
