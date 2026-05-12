<?php

declare(strict_types=1);

namespace App\Modules\Shared\Http\Controllers\Admin;

use App\Modules\Shared\Http\Controllers\Controller;
use App\Modules\Shared\Domain\Models\Announcement;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class AnnouncementController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Admin/Announcements/Index', [
            'announcements' => Announcement::query()
                ->latest()
                ->paginate(15)
                ->through(fn (Announcement $announcement) => [
                    'uuid' => $announcement->uuid,
                    'title' => $announcement->title,
                    'published_at' => $announcement->published_at?->toDateTimeString(),
                    'created_at' => $announcement->created_at?->toDateTimeString(),
                ]),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Admin/Announcements/Create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string'],
            'publish_now' => ['nullable', 'boolean'],
        ]);

        Announcement::create([
            'user_id' => $request->user()?->id,
            'title' => $data['title'],
            'body' => $data['body'],
            'published_at' => ! empty($data['publish_now']) ? now() : null,
        ]);

        return redirect()
            ->route('admin.announcements.index', absolute: false)
            ->with('success', 'Announcement created.');
    }
}
