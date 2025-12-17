<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CardResource;
use App\Models\Card;
use App\Models\StudySet;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $cards = Card::query()
            ->whereHas('studySet', function ($q) use ($request) {
                $q->where('user_id', $request->user()->id);
            })
            ->when($request->filled('study_set_id'), function ($q) use ($request) {
                $q->where('study_set_id', $request->study_set_id);
            })
            ->when($request->filled('search'), function ($q) use ($request) {
                $q->where(function ($sub) use ($request) {
                    $sub->where('japanese_word', 'like', "%{$request->search}%")
                        ->orWhere('meaning', 'like', "%{$request->search}%");
                });
            })
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return CardResource::collection($cards);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Terima alias set_id untuk kompatibilitas lama
        if ($request->filled('set_id') && !$request->filled('study_set_id')) {
            $request->merge(['study_set_id' => $request->input('set_id')]);
        }

        $validated = $request->validate([
            'study_set_id' => 'required|exists:study_sets,id',
            'japanese_word' => 'required|string|max:255',
            'japanese_reading' => 'required|string|max:255',
            'meaning' => 'required|string',
            'example_sentence' => 'nullable|string',
            'pitch_accent' => 'nullable|string|max:255',
            'is_mastered' => 'boolean',
        ]);

        // Pastikan study set milik user yang sedang login
        $studySet = StudySet::where('id', $validated['study_set_id'])
            ->where('user_id', $request->user()->id)
            ->firstOrFail();

        $card = $studySet->cards()->create($validated);

        return new CardResource($card);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Card $card)
    {
        $this->authorizeCardOwnership($request, $card);
        return new CardResource($card);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Card $card)
    {
        // Terima alias set_id untuk kompatibilitas lama
        if ($request->filled('set_id') && !$request->filled('study_set_id')) {
            $request->merge(['study_set_id' => $request->input('set_id')]);
        }

        $validated = $request->validate([
            'study_set_id' => 'exists:study_sets,id',
            'japanese_word' => 'string|max:255',
            'japanese_reading' => 'string|max:255',
            'meaning' => 'string',
            'example_sentence' => 'nullable|string',
            'pitch_accent' => 'nullable|string|max:255',
            'is_mastered' => 'boolean',
        ]);

        $this->authorizeCardOwnership($request, $card);

        // Jika berpindah set, pastikan set baru milik user
        if (isset($validated['study_set_id'])) {
            StudySet::where('id', $validated['study_set_id'])
                ->where('user_id', $request->user()->id)
                ->firstOrFail();
        }

        $card->update($validated);

        return new CardResource($card);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Card $card)
    {
        $this->authorizeCardOwnership($request, $card);
        $card->delete();

        return response()->noContent();
    }

    /**
     * Pastikan card milik user yang login
     */
    protected function authorizeCardOwnership(Request $request, Card $card): void
    {
        $ownsCard = $card->studySet()
            ->where('user_id', $request->user()->id)
            ->exists();

        abort_unless($ownsCard, Response::HTTP_FORBIDDEN, 'Unauthorized');
    }
}
