<?php

namespace App\Actions\Match;

use App\Actions\ActionInterface;
use App\Match;

class CreateMatchRecordAction implements ActionInterface
{

    private $data;

    /**
     * CreateMatchRecordAction constructor.
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function execute(): Match
    {
        $firstScore = $this->data['team_1']['score'];
        $secondScore = $this->data['team_2']['score'];

        // Create a new match record
        $match = new Match();
        $match->fill([
            'league_id' => $this->data['league_id'],
            'higher_score' => $firstScore >= $secondScore ? $firstScore : $secondScore,
            'lower_score' => $firstScore < $secondScore ? $firstScore : $secondScore,
        ]);
        $match->save();

        return $match;
    }
}
