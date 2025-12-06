<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Set;
use Illuminate\Http\Request;

class SetController extends Controller
{
    // GET /sets
    public function index(Request $request)
    {
        $sets = Set::where('user_id', $request->user()->id)->get();

        return response()->json($sets);
    }

    // POST /sets
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'description' => 'nullable|string'
        ]);

        $set = Set::create([
            'user_id' => $request->user()->id,
            'title'   => $request->title,
            'description' => $request->description
        ]);

        return response()->json($set, 201);
    }

    // GET /sets/{id}
    public function show(Request $request, $id)
    {
        $set = Set::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->with('cards')
            ->firstOrFail();

        return response()->json($set);
    }

    // PUT/PATCH /sets/{id}
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string',
            'description' => 'nullable|string'
        ]);

        $set = Set::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->firstOrFail();

        $updateData = [
            'title' => $request->title
        ];

        if ($request->has('description')) {
            $updateData['description'] = $request->description;
        }

        $set->update($updateData);
        $set->refresh();

        return response()->json($set);
    }

    // DELETE /sets/{id}
    public function destroy(Request $request, $id)
    {
        $set = Set::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->firstOrFail();

        $set->delete();

        return response()->json([
            'message' => 'Set deleted successfully.'
        ]);
    }
}
