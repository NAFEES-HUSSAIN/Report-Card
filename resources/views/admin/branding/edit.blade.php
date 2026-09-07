@extends('layouts.app')

@section('title', 'School logo')

@section('content')
<header class="mb-8">
    <h1 class="page-title">School logo</h1>
    <p class="page-subtitle">
        Upload a logo from your computer. Only the file path is saved in the database
        (<code class="text-xs">site_settings.logo_path</code>). The image file is stored under
        <code class="text-xs">storage/app/public/branding/</code>.
    </p>
</header>

<section class="card mb-6 space-y-4">
    <h2 class="font-display text-lg font-semibold">Current logo</h2>
    <div class="flex items-center gap-4">
        <x-brand-logo size="lg" />
        <div class="text-sm text-[var(--gs-muted)]">
            @if ($logoPath)
                <p>Database path: <span class="font-mono text-xs text-[var(--gs-ink)]">{{ $logoPath }}</span></p>
                <p class="mt-1">Public URL uses <span class="font-mono text-xs">/storage/...</span> via the storage link.</p>
            @else
                <p>No custom logo yet — showing the default <strong>GS</strong> mark.</p>
            @endif
        </div>
    </div>
</section>

<form method="POST" action="{{ route('admin.branding.update') }}" enctype="multipart/form-data" class="card space-y-5">
    @csrf
    @method('PUT')

    <div>
        <label for="logo" class="input-label">Choose logo from your PC</label>
        <input type="file" name="logo" id="logo" accept="image/png,image/jpeg,image/webp,image/svg+xml" class="input-field" required>
        @error('logo')<p class="field-error">{{ $message }}</p>@enderror
        <p class="mt-2 text-xs text-[var(--gs-muted)]">
            PNG, JPG, or WEBP. Max 2 MB. Prefer a <strong>PNG with a transparent background</strong>.
            Circular logos are clipped to a circle so square/black corners do not show.
        </p>
    </div>

    <button type="submit" class="btn-primary">Upload &amp; save logo</button>
</form>

@if ($logoPath)
    <form method="POST" action="{{ route('admin.branding.destroy') }}" class="mt-6" onsubmit="return confirm('Remove the school logo from storage and the database?')">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn-danger">Remove logo</button>
    </form>
@endif

<section class="mt-8 rounded-2xl border border-dashed border-[var(--gs-line)] p-5 text-sm text-[var(--gs-muted)]">
    <p class="font-semibold text-[var(--gs-ink)]">Where files live (senior setup)</p>
    <ul class="mt-3 list-disc space-y-1 ps-5">
        <li><strong>Upload here</strong> in Admin → School logo (recommended).</li>
        <li><strong>Disk file:</strong> <code>storage/app/public/branding/your-file.png</code></li>
        <li><strong>Database:</strong> row key <code>logo_path</code> in table <code>site_settings</code> (path string only, not the image bytes).</li>
        <li><strong>Remove:</strong> deletes the file from disk and clears the DB value so nothing is left orphaned.</li>
    </ul>
</section>
@endsection
