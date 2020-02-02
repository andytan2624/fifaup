<?php

namespace App\Actions\League;

use App\Actions\ActionInterface;
use App\League;
use App\Match;
use App\Score;
use App\User;
use Carbon\Carbon;

class RetrieveLeagueScoreboardAction implements ActionInterface
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
        $matches = $this->league->matches;


        $newScores = $this->league->scores()->whereDate('scores.created_at', '>=', Carbon::now()->subMonth(1)->toDateTimeString())->get();

        $scoreTally = [];
        foreach ($newScores as $score) {
            if (!isset($scoreTally[$score->user_id])) {
                $scoreTally[$score->user_id] = 0;
            }

            switch ($score->status) {
                case Score::STATUS_WIN:
                    $scoreTally[$score->user_id] += !$score->is_team ? 3 : 2;
                break;
                case Score::STATUS_DRAW:
                    $scoreTally[$score->user_id] += 1;
                break;
            }
        }

        // Sort the array by points in descending order
        arsort($scoreTally);

        $transformedArray = [];

        foreach ($scoreTally as $user_id => $points) {
            $transformedArray[] = [
                'user' => User::find($user_id)->name,
                'points' => $points,
            ];
        }

        return $transformedArray;
    }
}
