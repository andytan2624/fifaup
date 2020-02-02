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
        // TODO: we need some validation on the data here

        $team1Data = $this->data['team_1'];
        $team2Data = $this->data['team_2'];

        // Create a new match record
        $match = new Match();
        $match->fill([
            'league_id' => $this->data['league_id'],
            'team_1_score' => $team1Data['score'],
            'team_2_score' => $team2Data['score'],
        ]);
        $match->save();

        return $match;
    }
}
