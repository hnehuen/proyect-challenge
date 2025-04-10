<?php

namespace App\Http\Controllers;

use App\Models\Player;
use Illuminate\Http\Request;

class PlayerController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/v1/players",
     *     summary="Listar todos los jugadores",
     *     tags={"Players"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de jugadores"
     *     )
     * )
     */
    public function index()
    {
        return Player::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'gender'         => 'required|in:Masculino,Femenino',
            'skill_level'    => 'required|integer|between:0,100',
            'strength'       => 'nullable|integer|between:0,100|required_if:gender,Masculino',
            'speed'          => 'nullable|integer|between:0,100|required_if:gender,Masculino',
            'reaction_time'  => 'nullable|integer|between:0,100|required_if:gender,Femenino',
        ]);

        $player = Player::create($validated);

        return response()->json($player, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
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
    public function destroy(string $id)
    {
        //
    }
}
