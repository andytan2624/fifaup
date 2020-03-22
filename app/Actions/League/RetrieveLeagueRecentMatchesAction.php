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
            $resultMatch = [
                'winner' => null,
                'loser' => null,
                'draw' => null,
                'date' => Carbon::createFromFormat('Y-m-d H:i:s', $match->created_at)->diffForHumans(),
            ];

            $team1 = [
                'score' => 0,
                'users' => [],
            ];
            $team2 = [
                'score' => 0,
                'users' => [],
            ];

            /** @var Score $team1Score */
            $team1Scores = $match->team1Scores();
            /** @var Score $team2Score */
            $team2Scores = $match->team2Scores();

            /** @var Score $score */
            foreach ($team1Scores->get() as $score) {
                $team1['score'] = $score->points;
                $team1['users'][] = $score->user->name;
            }

            /** @var Score $score */
            foreach ($team2Scores->get() as $score) {
                $team2['score'] = $score->points;
                $team2['users'][] = $score->user->name;
            }

            if ($team1['score'] > $team2['score']) {
                $resultMatch['winner'] = $team1;
                $resultMatch['loser'] = $team2;
            } elseif ($team2['score'] > $team1['score']) {
                $resultMatch['winner'] = $team2;
                $resultMatch['loser'] = $team1;
            } else {
                $resultMatch['draw'] = [];
                $resultMatch['draw'][] = $team1;
                $resultMatch['draw'][] = $team2;
            }

            $transformedArray[] = $resultMatch;
        }

        return $transformedArray;
    }
}
