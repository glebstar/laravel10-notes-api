<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Services\NoteService;
use App\Models\Note;
use App\Http\Resources\NoteResource;
use App\Http\Requests\NoteStoreRequest;

class NoteController extends Controller
{
    /**
     * @var NoteService
     */
    private $noteService;

    public function __construct(NoteService $noteService)
    {
        $this->middleware('auth:api');
        $this->noteService = $noteService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(NoteStoreRequest $request): JsonResponse
    {
        $note = Note::create([
            'text' => $request->note,
            'user_id' => auth()->user()->id,
        ]);

        return response()->json(NoteResource::make($note));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): JsonResponse
    {
        $note = Note::where ('id', $id)
            ->first ();

        if(! $note) {
            return response ()->json (['error' => 'not found'], 404);
        }

        if ($note->user_id != auth()->user()->id) {
            return response ()->json (['error' => 'not access'], 401);
        }

        $note->delete();
        return response ()->json (['deleted' => $id]);
    }

    /**
     * Recovers deleted note
     *
     * @param integer $id Note ID
     *
     * @return JsonResponse
     */
    public function restore(int $id): JsonResponse
    {
        $note = Note::withTrashed()
            ->where ('id', $id)
            ->first ();

        if (! $note) {
            return response ()->json (['error' => 'not found'], 404);
        }

        if ($note->user_id != auth()->user()->id) {
            return response ()->json (['error' => 'not access'], 401);
        }

        $note->restore();
        return response ()->json (NoteResource::make($note));
    }
}
