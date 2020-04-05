<?php

namespace App\Actions\Match;

use App\Actions\ActionInterface;
use App\Match;
use App\ScoreType;

class CreateMatchRecordAction implements ActionInterface
{

    private $data;
    private $scoreType;

    /**
     * CreateMatchRecordAction constructor.
     * @param array $data
     * @param string|null $scoreType
     */
    public function __construct(array $data, string $scoreType = null)
    {
        $this->data = $data;
        $this->scoreType = $scoreType;
    }

    public function execute(): Match
    {
        // Create a new match record
        $match = new Match();

        $data = [
            'league_id' => $this->data['league_id'],
        ];

        if ($this->scoreType === ScoreType::VERSUS) {
            $firstScore = $this->data['team_1']['score'];
            $secondScore = $this->data['team_2']['score'];

            $data['higher_score'] = $firstScore >= $secondScore ? $firstScore : $secondScore;
            $data['lower_score'] = $firstScore < $secondScore ? $firstScore : $secondScore;
        }

        $match->fill($data);

        $match->save();

        return $match;
    }
}
