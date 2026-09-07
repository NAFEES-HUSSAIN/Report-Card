<?php

use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

it('lets an admin upload a school logo and stores only the path in the database', function () {
    Storage::fake('public');

    $admin = User::factory()->admin()->create();
    $file = UploadedFile::fake()->image('school-logo.png', 120, 120);

    $this->actingAs($admin)
        ->put(route('admin.branding.update'), [
            'logo' => $file,
        ])
        ->assertRedirect(route('admin.branding.edit'));

    $path = SiteSetting::logoPath();

    expect($path)->not->toBeNull()
        ->and($path)->toStartWith('branding/')
        ->and(Storage::disk('public')->exists($path))->toBeTrue();

    $this->assertDatabaseHas('site_settings', [
        'key' => SiteSetting::LogoPath,
        'value' => $path,
    ]);
});

it('removes the logo file from storage and clears the database path', function () {
    Storage::fake('public');

    $admin = User::factory()->admin()->create();
    $path = UploadedFile::fake()->image('old-logo.png')->store('branding', 'public');
    SiteSetting::setValue(SiteSetting::LogoPath, $path);

    $this->actingAs($admin)
        ->delete(route('admin.branding.destroy'))
        ->assertRedirect(route('admin.branding.edit'));

    expect(Storage::disk('public')->exists($path))->toBeFalse()
        ->and(SiteSetting::logoPath())->toBeNull();
});

it('shows the branding page to admins', function () {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)
        ->get(route('admin.branding.edit'))
        ->assertOk()
        ->assertSee('School logo');
});
