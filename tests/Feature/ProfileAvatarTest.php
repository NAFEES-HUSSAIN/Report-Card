<?php

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

it('uploads a profile photo, stores only the path, and shows it on the profile page and topbar', function () {
    Storage::fake('public');

    $teacher = User::factory()->teacher()->create();
    $photo = UploadedFile::fake()->image('teacher-photo.jpg', 200, 200);

    $this->actingAs($teacher)
        ->put(route('teacher.profile.update'), [
            'name' => $teacher->name,
            'username' => $teacher->username,
            'email' => $teacher->email,
            'avatar' => $photo,
        ])
        ->assertRedirect(route('teacher.profile.edit'));

    $teacher->refresh();

    expect($teacher->avatar_path)->not->toBeNull()
        ->and($teacher->hasCustomAvatar())->toBeTrue()
        ->and(Storage::disk('public')->exists($teacher->avatar_path))->toBeTrue();

    // Profile page + topbar both render the uploaded avatar after save/redirect.
    $this->actingAs($teacher)
        ->get(route('teacher.profile.edit'))
        ->assertOk()
        ->assertSee('/storage/'.$teacher->avatar_path, false)
        ->assertSee('data-user-avatar="'.$teacher->id.'"', false);
});

it('replaces an old avatar file instead of leaving duplicates', function () {
    Storage::fake('public');

    $admin = User::factory()->admin()->create();
    $oldPath = UploadedFile::fake()->image('old.jpg')->store('avatars', 'public');
    $admin->forceFill(['avatar_path' => $oldPath])->save();

    $this->actingAs($admin)
        ->put(route('admin.profile.update'), [
            'name' => $admin->name,
            'username' => $admin->username,
            'email' => $admin->email,
            'avatar' => UploadedFile::fake()->image('new.jpg'),
        ])
        ->assertRedirect(route('admin.profile.edit'));

    $admin->refresh();

    expect(Storage::disk('public')->exists($oldPath))->toBeFalse()
        ->and($admin->avatar_path)->not->toBe($oldPath)
        ->and(Storage::disk('public')->exists($admin->avatar_path))->toBeTrue();
});

it('removes the avatar file and falls back to the default image', function () {
    Storage::fake('public');

    $teacher = User::factory()->teacher()->create();
    $path = UploadedFile::fake()->image('remove-me.jpg')->store('avatars', 'public');
    $teacher->forceFill(['avatar_path' => $path])->save();

    $this->actingAs($teacher)
        ->put(route('teacher.profile.update'), [
            'name' => $teacher->name,
            'username' => $teacher->username,
            'email' => $teacher->email,
            'remove_avatar' => '1',
        ])
        ->assertRedirect(route('teacher.profile.edit'));

    $teacher->refresh();

    expect($teacher->avatar_path)->toBeNull()
        ->and(Storage::disk('public')->exists($path))->toBeFalse()
        ->and($teacher->profilePhotoUrl())->toContain('images/defaults/user-avatar.png');
});
