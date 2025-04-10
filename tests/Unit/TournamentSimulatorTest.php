<?php

namespace Tests\Unit;

use App\Models\Player;
use App\Models\Tournament;
use App\Services\TournamentSimulator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TournamentSimulatorTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function simula_un_torneo_masculino_y_genera_un_ganador()
    {
        // Crear torneo
        $tournament = Tournament::factory()->create([
            'name' => 'Torneo Test',
            'category' => 'Masculino',
        ]);

        // Crear jugadores masculinos
        $players = Player::factory()->count(4)->create([
            'gender' => 'Masculino',
        ])->each(function ($player) {
            $player->update([
                'skill_level' => rand(70, 100),
                'strength' => rand(70, 100),
                'speed' => rand(70, 100),
            ]);
        });

        // Asignar jugadores al torneo
        $tournament->players()->attach($players->pluck('id'));

        // Simular torneo
        $simulator = new TournamentSimulator();
        $result = $simulator->simulate($tournament->fresh());

        // Asserts
        $this->assertNotEmpty($result['winner']);
        $this->assertEquals('finished', $tournament->fresh()->status);
        $this->assertNotNull($tournament->fresh()->winner_id);
        $this->assertArrayHasKey('history', $result);
        $this->assertGreaterThanOrEqual(1, count($result['history']));
    }
}
