<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OlympicGame;

class OlympicGameController extends Controller
{
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

        // Обработка загруженного изображения
        if ($request->hasFile('image_upload')) {
            $image = $request->file('image_upload');
            
            // Генерируем уникальное имя файла
            $extension = $image->getClientOriginalExtension();
            $imageName = uniqid() . '_' . time() . '.' . $extension;
            
            // Сохраняем файл в public/images
            $image->move(public_path('images'), $imageName);
            
            // Сохраняем имя файла в БД
            $validated['image_filename'] = $imageName;
            
            // Удаляем временное поле из массива
            unset($validated['image_upload']);
        }

        // Создаем новую запись
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
        return view('olympic-games.modal-content', compact('game'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
