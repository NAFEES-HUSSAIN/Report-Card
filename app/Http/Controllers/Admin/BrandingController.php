<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use App\Support\SystemPermissions;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BrandingController extends Controller
{
    public function edit(): View
    {
        abort_unless(auth()->user()?->hasPermission(SystemPermissions::AdminProfiles), 403);

        return view('admin.branding.edit', [
            'shellRole' => 'admin',
            'logoUrl' => SiteSetting::logoUrl(),
            'logoPath' => SiteSetting::logoPath(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        abort_unless(auth()->user()?->hasPermission(SystemPermissions::AdminProfiles), 403);

        $validated = $request->validate([
            'logo' => ['required', 'image', 'mimes:png,jpg,jpeg,webp', 'max:2048'],
        ]);

        SiteSetting::storeLogo($validated['logo']);

        return redirect()
            ->route('admin.branding.edit')
            ->with('success', 'School logo updated. It will show across GradeSphere.');
    }

    public function destroy(): RedirectResponse
    {
        abort_unless(auth()->user()?->hasPermission(SystemPermissions::AdminProfiles), 403);

        SiteSetting::removeLogo();

        return redirect()
            ->route('admin.branding.edit')
            ->with('success', 'School logo removed. The default GS mark is back.');
    }
}
