<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReachoutRequest;
use App\Models\Reachout;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ReachoutController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $this->authorize('viewAny', Reachout::class);

        return Reachout::all();
    }

    public function store(ReachoutRequest $request)
    {
        $this->authorize('create', Reachout::class);

        return Reachout::create($request->validated());
    }

    public function show(Reachout $reachout)
    {
        $this->authorize('view', $reachout);

        return $reachout;
    }

    public function update(ReachoutRequest $request, Reachout $reachout)
    {
        $this->authorize('update', $reachout);

        $reachout->update($request->validated());

        return $reachout;
    }

    public function destroy(Reachout $reachout)
    {
        $this->authorize('delete', $reachout);

        $reachout->delete();

        return response()->json();
    }
}
