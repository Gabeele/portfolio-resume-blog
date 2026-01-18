<?php

namespace App\Http\Controllers;

use App\Models\Analytic;
use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;

class LinksController extends Controller
{
    public function show(User $user): Response
    {
        $links = $user->links()
            ->where('is_active', true)
            ->orderBy('order')
            ->get();

        // Track links page view
        Analytic::track('links_view', $user);

        return Inertia::render("templates/{$user->template->value}/Links/Show", [
            'user' => $user,
            'links' => $links,
        ]);
    }
}
