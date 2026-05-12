<?php

declare(strict_types=1);

namespace App\Modules\Shared\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ThemeController extends Controller
{
    /**
     * Toggle between dark and light theme modes.
     */
    public function toggle(Request $request): RedirectResponse
    {
        $currentMode = session('theme_mode', 'dark');
        $newMode = $currentMode === 'dark' ? 'light' : 'dark';

        session(['theme_mode' => $newMode]);

        return back();
    }

    /**
     * Set theme mode explicitly.
     */
    public function set(Request $request): RedirectResponse
    {
        $mode = $request->validate([
            'mode' => 'required|in:dark,light',
        ]);

        session(['theme_mode' => $mode['mode']]);

        return back();
    }
}
