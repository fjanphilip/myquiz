<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Card;
use App\Http\Resources\CardResource;
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
        $validated = $request->validate([
            'study_set_id' => 'required|exists:study_sets,id',
            'japanese_word' => 'required|string|max:255',
            'japanese_reading' => 'required|string|max:gg255',
            'meaning' => 'required|string',
            'example_sentence' => 'nullable|string',
            'pitch_accent' => 'nullable|string|max:255',
            'is_mastered' => 'boolean',
        ]);

        $card = Card::create($validated);

        return new CardResource($card);
    }

    /**
     * Display the specified resource.
     */
    public function show(Card $card)
    {
        return new CardResource($card);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Card $card)
    {
        $validated = $request->validate([
            'study_set_id' => 'exists:study_sets,id',
            'japanese_word' => 'string|max:255',
            'japanese_reading' => 'string|max:255',
            'meaning' => 'string',
            'example_sentence' => 'nullable|string',
            'pitch_accent' => 'nullable|string|max:255',
            'is_mastered' => 'boolean',
        ]);

        $card->update($validated);

        return new CardResource($card);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Card $card)
    {
        $card->delete();

        return response()->noContent();
    }
}
