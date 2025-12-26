<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OlympicGame;
use App\Services\ImageService;
use Illuminate\Support\Facades\Gate;

class OlympicGameController extends Controller
{
    protected $imageService;

    public function __construct()
    {
        $this->imageService = new ImageService();
        $this->middleware('auth')->except(['index', 'show']);
    }

    public function index(Request $request)
    {
        $sortOrder = $request->get('sort', 'desc');
        $sortOrder = in_array(strtolower($sortOrder), ['asc', 'desc']) ? $sortOrder : 'desc';
        
        $games = OlympicGame::orderBy('year', $sortOrder)->get();
        return view('welcome', compact('games'));
    }

    public function create()
    {
        return view('olympic-games.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'year' => 'required|integer|min:1900|max:2030',
            'short_description' => 'required|string|max:500',
            'detailed_description' => 'required|string',
            'fun_fact' => 'required|string|max:300',
            'image_upload' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048' // 2MB max
        ]);

        if ($request->hasFile('image_upload')) {
            $imageName = $this->imageService->saveSquareImage($request->file('image_upload'));
            $validated['image_filename'] = $imageName;
            unset($validated['image_upload']);
        }

        // OlympicGame::create($validated);
        auth()->user()->olympicGames()->create($validated);

        return redirect("/")->with('success', 'Олимпийские игры успешно добавлены!');
    }

    public function show($id)
    {
        if (auth()->check() && auth()->user()->is_admin) {
            $game = OlympicGame::withTrashed()->findOrFail($id);
        } else {
            $game = OlympicGame::findOrFail($id);
        }
        return view('olympic-games.show', compact('game'));
    }

    public function edit($id)
    {
        $game = OlympicGame::findOrFail($id);
        if (!Gate::allows('edit-game', $game)) {
            abort(403, 'У вас нет прав для редактирования этой записи');
        }
        return view('olympic-games.edit', compact('game'));
    }

    public function update(Request $request, $id)
    {
        $game = OlympicGame::findOrFail($id);
        if (!Gate::allows('edit-game', $game)) {
            abort(403, 'У вас нет прав для редактирования этой записи');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'year' => 'required|integer|min:1900|max:2060',
            'short_description' => 'required|string|max:500',
            'detailed_description' => 'required|string',
            'fun_fact' => 'required|string|max:300',
            'image_upload' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048'
        ]);

        if ($request->hasFile('image_upload')) {
            $imageName = $this->imageService->saveSquareImage($request->file('image_upload'));
            $validated['image_filename'] = $imageName;
            unset($validated['image_upload']);
        }

        $game->update($validated);

        return redirect()->route('olympic-games.show', $id)
            ->with('success', 'Олимпийские игры успешно обновлены!');
    }

    public function destroy($id)
    {
        $game = OlympicGame::findOrFail($id);

        if (!Gate::allows('delete-game', $game)) {
            abort(403, 'Вы можете удалять только свои записи');
        }

        $game->delete();

        return redirect("/")->with('success', 'Олимпийские игры успешно удалены!');
    }

    public function forceDelete($id)
    {
        if (!Gate::allows('admin')) {
            abort(403, 'Только администратор может полностью удалять записи');
        }

        $game = OlympicGame::withTrashed()->findOrFail($id);
        
        $game->forceDelete();

        return redirect()->back()->with('success', 'Олимпийские игры полностью удалены!');
    }

    public function restore($id)
    {
        if (!Gate::allows('admin')) {
            abort(403, 'Только администратор может восстанавливать записи');
        }

        $game = OlympicGame::withTrashed()->findOrFail($id);
        
        if (!$game->trashed()) {
            return redirect()->back()->with('error', 'Эта запись не была удалена');
        }
        
        $game->restore();

        return redirect()->back();
    }
}
