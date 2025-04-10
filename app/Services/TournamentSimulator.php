<?php

namespace App\Services;

use App\Models\Tournament;
use Illuminate\Support\Collection;

class TournamentSimulator
{
    public function simulate(Tournament $tournament): array
    {
        $players = $tournament->players()->get()->shuffle();

        $round = 1;
        $history = [];

        while ($players->count() > 1) {
            $nextRound = collect();
            $roundResults = [];

            for ($i = 0; $i < $players->count(); $i += 2) {
                $p1 = $players[$i];
                $p2 = $players[$i + 1] ?? null;

                if (!$p2) {
                    // Jugador pasa automáticamente si no hay contrincante
                    $nextRound->push($p1);
                    continue;
                }

                $score1 = $this->calculateScore($p1, $tournament->category);
                $score2 = $this->calculateScore($p2, $tournament->category);

                $winner = $score1 >= $score2 ? $p1 : $p2;
                $loser  = $score1 >= $score2 ? $p2 : $p1;

                $roundResults[] = [
                    'match' => "{$p1->name} vs {$p2->name}",
                    'winner' => $winner->name,
                    'loser' => $loser->name,
                    'score' => "{$score1} - {$score2}",
                ];

                $nextRound->push($winner);
            }

            $history["Ronda {$round}"] = $roundResults;
            $players = $nextRound;
            $round++;
        }

        $winner = $players->first();
        $tournament->winner_id = $winner->id;
        $tournament->status = 'finished';
        $tournament->save();

        return [
            'winner' => $winner->name,
            'tournament' => $tournament->name,
            'history' => $history,
        ];
    }

    private function calculateScore($player, $category): int
    {
        if ($category === 'Masculino') {
            return $player->skill_level + $player->strength + $player->speed;
        } else {
            return $player->skill_level + $player->reaction_time;
        }
    }
}
