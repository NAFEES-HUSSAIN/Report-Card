<?php

namespace App\Models;

use App\Enums\UserRole;
use App\Support\SystemPermissions;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;

#[Fillable(['name', 'username', 'email', 'avatar_path', 'password', 'role', 'is_active'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => UserRole::class,
            'is_active' => 'boolean',
        ];
    }

    public function reportCardsCreated(): HasMany
    {
        return $this->hasMany(ReportCard::class, 'created_by');
    }

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class)->withTimestamps();
    }

    public function isTeacher(): bool
    {
        return $this->role === UserRole::Teacher || $this->role === UserRole::Admin;
    }

    public function isAdmin(): bool
    {
        return $this->role === UserRole::Admin;
    }

    public function hasPermission(string $key): bool
    {
        if ($this->isAdmin()) {
            return true;
        }

        if (! $this->is_active) {
            return false;
        }

        if ($this->relationLoaded('permissions')) {
            return $this->permissions->contains('key', $key);
        }

        return $this->permissions()->where('key', $key)->exists();
    }

    /**
     * @param  list<string>  $keys
     */
    public function syncPermissionKeys(array $keys): void
    {
        $ids = Permission::query()->whereIn('key', $keys)->pluck('id');
        $this->permissions()->sync($ids);
    }

    public function grantDefaultTeacherPermissions(): void
    {
        $this->syncPermissionKeys(SystemPermissions::teacherKeys());
    }

    public function hasCustomAvatar(): bool
    {
        return filled($this->avatar_path) && Storage::disk('public')->exists($this->avatar_path);
    }

    public function avatarUrl(): ?string
    {
        if (! $this->hasCustomAvatar()) {
            return null;
        }

        return Storage::disk('public')->url($this->avatar_path)
            .'?v='.($this->updated_at?->getTimestamp() ?? time())
            .'&p='.sha1((string) $this->avatar_path);
    }

    /**
     * Always returns a displayable photo URL (uploaded avatar or default image).
     */
    public function profilePhotoUrl(): string
    {
        return $this->avatarUrl() ?? asset('images/defaults/user-avatar.png');
    }

    public function deleteStoredAvatar(): void
    {
        if (filled($this->avatar_path) && Storage::disk('public')->exists($this->avatar_path)) {
            Storage::disk('public')->delete($this->avatar_path);
        }

        $this->avatar_path = null;
    }
}
