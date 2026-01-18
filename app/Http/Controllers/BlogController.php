<?php

namespace App\Http\Controllers;

use App\Models\Analytic;
use App\Models\Post;
use App\Models\Scopes\CurrentUserScope;
use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;

class BlogController extends Controller
{
    public function index(User $user): Response
    {
        $posts = Post::withoutGlobalScope(CurrentUserScope::class)
            ->where('user_id', $user->id)
            ->where('publish', true)
            ->with('tags')
            ->latest('published_at')
            ->paginate(10);

        // Track blog page view
        Analytic::track('blog_view', $user);

        return Inertia::render('templates/standard/Blog/Index', [
            'user' => $user,
            'posts' => $posts,
        ]);
    }

    public function show(User $user, Post $post): Response
    {
        abort_if($post->user_id !== $user->id, 404);
        abort_if(!$post->publish, 404);

        $post->load('tags');

        // Track blog post view
        Analytic::track('blog_post_view', $user, $post);


        return Inertia::render("templates/{$user->template->value}/Blog/Show", [
            'user' => $user,
            'post' => $post,
        ]);
    }
}
