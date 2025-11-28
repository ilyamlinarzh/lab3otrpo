<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OlympicGame;
use App\Services\ImageService;

class OlympicGameController extends Controller
{
    protected $imageService;

    public function __construct()
    {
        // Создаем экземпляр сервиса напрямую
        $this->imageService = new ImageService();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $games = OlympicGame::orderBy('year', 'desc')->get();
        return view('welcome', compact('games'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('olympic-games.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
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

        OlympicGame::create($validated);

        return redirect()->route('olympic-games.index')
            ->with('success', 'Олимпийские игры успешно добавлены!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $game = OlympicGame::findOrFail($id);
        return view('olympic-games.show', compact('game'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $game = OlympicGame::findOrFail($id);
        return view('olympic-games.edit', compact('game'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $game = OlympicGame::findOrFail($id);
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $game = OlympicGame::findOrFail($id);

        $game->delete();

        return redirect()->route('olympic-games.index')
            ->with('success', 'Олимпийские игры успешно удалены!');
    }
}
