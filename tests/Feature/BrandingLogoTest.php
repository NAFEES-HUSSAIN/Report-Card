<?php

use App\Models\User;
use App\Support\BrandAssets;
use Illuminate\Support\Facades\Route;

it('serves a static school logo from public branding assets', function () {
    expect(BrandAssets::schoolLogoUrl())->toContain('images/branding/school-logo');
});

it('shows the school logo on the splash screen', function () {
    $this->get(route('splash'))
        ->assertOk()
        ->assertSee('images/branding/school-logo', false)
        ->assertSee('alt="School logo"', false);
});

it('shows the school logo in the admin sidebar and has no branding upload route', function () {
    $admin = User::factory()->admin()->create();

    expect(Route::has('admin.branding.edit'))->toBeFalse();

    $this->actingAs($admin)
        ->get(route('admin.dashboard'))
        ->assertOk()
        ->assertSee('images/branding/school-logo', false)
        ->assertSee('h-14 w-14', false);

    $this->actingAs($admin)
        ->get('/admin/branding')
        ->assertNotFound();
});
