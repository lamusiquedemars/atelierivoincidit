<?php

namespace App\Modules\SiteSettings\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    protected $fillable = [
        'site_name',
        'baseline',
        'default_seo_title',
        'default_seo_description',
        'contact_email',
        'phone',
        'address',
        'logo_path',
        'favicon_path',
        'default_og_image_path',
        'social_links',
        'contact_form_show_name',
        'contact_form_show_phone',
        'contact_form_show_subject',
        'contact_form_send_admin_email',
        'contact_form_send_confirmation_email',
    ];

    protected function casts(): array
    {
        return [
            'social_links' => 'array',
            'contact_form_show_name' => 'boolean',
            'contact_form_show_phone' => 'boolean',
            'contact_form_show_subject' => 'boolean',
            'contact_form_send_admin_email' => 'boolean',
            'contact_form_send_confirmation_email' => 'boolean',
        ];
    }

    public function getContactFormShowNameAttribute(?bool $value): bool
    {
        return $value ?? false;
    }

    public function getContactFormShowPhoneAttribute(?bool $value): bool
    {
        return $value ?? false;
    }

    public function getContactFormShowSubjectAttribute(?bool $value): bool
    {
        return $value ?? false;
    }

    public function getContactFormSendAdminEmailAttribute(?bool $value): bool
    {
        return $value ?? true;
    }

    public function getContactFormSendConfirmationEmailAttribute(?bool $value): bool
    {
        return $value ?? false;
    }

    public static function current(): self
    {
        return static::query()->firstOrCreate([], [
            'site_name' => 'Maracuja CMS',
            'baseline' => 'Site vitrine administrable, sobre et sur mesure.',
            'default_seo_title' => 'Maracuja CMS',
            'default_seo_description' => 'Atelier Ivo Incidit présente des archets contemporains, des archets anciens et le savoir-faire d’un atelier dédié aux musiciens.',
            'default_og_image_path' => '/demo/theme-system.svg',
            'contact_email' => 'contact@example.test',
            'contact_form_show_name' => false,
            'contact_form_show_phone' => false,
            'contact_form_show_subject' => false,
            'contact_form_send_admin_email' => true,
            'contact_form_send_confirmation_email' => false,
        ]);
    }
}
