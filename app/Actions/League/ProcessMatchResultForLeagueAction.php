<?php

namespace App\Actions\League;

use App\Actions\ActionInterface;
use App\Actions\Match\CreateMatchRecordAction;
use App\Actions\Score\CreateScoreRecordAction;
use App\League;
use App\Match;
use App\Score;
use App\ScoreType;

class ProcessMatchResultForLeagueAction implements ActionInterface
{
    private $data;

    /**
     * ProcessMatchResultForLeagueAction constructor.
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * @return Match
     */
    public function execute(): Match
    {
        /** @var League $league */
        $league = League::find($this->data['league_id']);
        $scoreType = $league->getScoreTypeCode();

        $createMatchRecordAction = new CreateMatchRecordAction($this->data, $scoreType);
        $match = $createMatchRecordAction->execute();

        if ($scoreType === ScoreType::RUMBLE) {
            $createScoreRecord = new CreateScoreRecordAction($match, $this->data['users']);
            $createScoreRecord->execute();
        } else {
            $createTeam1ScoreRecord = new CreateScoreRecordAction($match, $this->data['team_1'], Score::TEAM_1);
            $createTeam1ScoreRecord->execute();

            $createTeam2ScoreRecord = new CreateScoreRecordAction($match, $this->data['team_2'], Score::TEAM_2);
            $createTeam2ScoreRecord->execute();
        }

        return $match;
    }
}
