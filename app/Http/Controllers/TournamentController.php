<?php

namespace App\Http\Controllers;

use App\Models\Tournament;
use App\Models\Player;
use App\Services\TournamentSimulator;
use Illuminate\Http\Request;

class TournamentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * @OA\Post(
     *     path="/api/v1/tournaments",
     *     summary="Crear un nuevo torneo",
     *     tags={"Tournaments"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "category", "player_ids"},
     *             @OA\Property(property="name", type="string", example="Copa Invencible"),
     *             @OA\Property(property="category", type="string", enum={"Masculino", "Femenino"}, example="Masculino"),
     *             @OA\Property(property="player_ids", type="array", @OA\Items(type="integer"), example={1,2,3,4})
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Torneo creado correctamente"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación"
     *     )
     * )
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'       => 'required|string|max:255',
            'category'   => 'required|in:Masculino,Femenino',
            'player_ids' => 'required|array|min:2',
            'player_ids.*' => 'exists:players,id',
        ]);

        // Verificar que todos los jugadores coincidan con la categoría
        $players = Player::whereIn('id', $validated['player_ids'])->get();

        $invalid = $players->filter(fn($p) => $p->gender !== $validated['category']);

        if ($invalid->count()) {
            return response()->json([
                'error' => 'Todos los jugadores deben ser del mismo género que el torneo.'
            ], 422);
        }

        $tournament = Tournament::create([
            'name'     => $validated['name'],
            'category' => $validated['category'],
        ]);

        $tournament->players()->attach($validated['player_ids']);

        return response()->json([
            'message' => 'Torneo creado correctamente',
            'tournament' => $tournament->load('players'),
        ], 201);
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

    /**
     * @OA\Post(
     *     path="/api/v1/tournaments/{id}/simulate",
     *     summary="Simular el torneo",
     *     tags={"Tournaments"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del torneo",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Resultado de la simulación"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="El torneo ya fue simulado"
     *     )
     * )
     */
    public function simulate($id)
    {
        $tournament = Tournament::with('players')->findOrFail($id);

        if ($tournament->status === 'finished') {
            return response()->json(['message' => 'Este torneo ya fue simulado.'], 400);
        }

        $simulator = new TournamentSimulator();
        $result = $simulator->simulate($tournament);

        return response()->json($result);
    }
}
