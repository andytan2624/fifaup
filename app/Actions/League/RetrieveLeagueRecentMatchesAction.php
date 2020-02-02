<?php

namespace App\Actions\League;

use App\Actions\ActionInterface;
use App\League;
use App\Match;
use App\Score;
use App\User;
use Carbon\Carbon;

class RetrieveLeagueRecentMatchesAction implements ActionInterface
{
    /** @var League $league */
    private $league;

    /**
     * CreateMatchRecordAction constructor.
     * @param array $data
     */
    public function __construct(string $leagueID)
    {
        $this->league = League::find($leagueID);
    }

    public function execute(): array
    {
        $matches = $this->league->matches()->limit(20)->get();

        $transformedArray = [];

        /** @var Match $match */
        foreach ($matches as $match) {

            $winningTeam = [
                'score' => 0,
                'users' => [],
            ];
            $losingTeam = [
                'score' => 0,
                'users' => [],
            ];

            // We want to order the response so the winning team is listed first with details
            if ($match->team_1_score >= $match->team_2_score) {
                $winningTeam['score'] = $match->team_1_score;

                /** @var Score $score */
                foreach ($match->team1Scores as $score) {

                    $winningTeam['users'][] = $score->user->name;
                }

                $losingTeam['score'] = $match->team_2_score;
                /** @var Score $score */
                foreach ($match->team2Scores as $score) {
                    $losingTeam['users'][] = $score->user->name;
                }

            } else {
                $winningTeam['score'] = $match->team_2_score;
                /** @var Score $score */
                foreach ($match->team2Scores as $score) {
                    $winningTeam['users'][] = $score->user->name;
                }

                $losingTeam['score'] = $match->team_1_score;
                /** @var Score $score */
                foreach ($match->team1Scores as $score) {
                    $losingTeam['users'][] = $score->user->name;
                }
            }

            $transformedArray[] = [
                'winningTeam' => $winningTeam,
                'losingTeam' => $losingTeam,
            ];
        }

        return $transformedArray;
    }
}
