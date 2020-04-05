<?php

namespace App\Actions\Slack;

use App\Actions\ActionInterface;
use App\League;
use App\Score;
use App\ScoreType;

class GetSlackPrettyRecentResultsAction implements ActionInterface
{
    private $league;
    private $data;

    /**
     * GetSlackPrettyRecentResultsAction constructor.
     * @param League $league
     * @param array $data
     */
    public function __construct(League $league, array $data)
    {
        $this->data = $data;
        $this->league = $league;
    }

    public function execute()
    {
        /** @var League $league */
        if ($this->league->getScoreTypeCode() === ScoreType::RUMBLE) {
            return $this->executeRumblePrettyRecentResults();
        }

        $output = [];

        $userMatches = "";

        foreach ($this->data as $index => $matchData) {
            $matchOutput = ($index + 1) . ". ";
            if (null !== $matchData['draw']) {
                $matchOutput .= implode(' & ', $matchData['draw'][0]['users']) . ' drew ' .
                    implode(' & ', $matchData['draw'][1]['users'])
                    . " *(" . $matchData['draw'][0]['score'] . " - " . $matchData['draw'][1]['score'] .")*";
            } else {
                $matchOutput .= implode(' & ', $matchData['winner']['users']) . ' defeated ' .
                    implode(' & ', $matchData['loser']['users'])
                    . " *(" . $matchData['winner']['score'] . " - " . $matchData['loser']['score'] .")*";
            }

            $matchOutput .= " - " . $matchData['date'];
            $userMatches .= "$matchOutput \n";
        }
        // Title of output
        $output[] =  [
            "type" => "section",
            "text" => [
                "type" => "mrkdwn",
                "text" => ":sports_medal: Latest Match Results \n" . $userMatches,
            ],
        ];

        return $output;
    }

    /**
     * @return array
     */
    public function executeRumblePrettyRecentResults() :array
    {
        $output = [];

        $userMatches = "";

        foreach ($this->data as $matchData) {
            $userMatches .= ":calendar: _$matchData[date]_ \n $matchData[users]\n\n\n";
        }

        // Title of output
        $output[] =  [
            "type" => "section",
            "text" => [
                "type" => "mrkdwn",
                "text" => ":sports_medal: Latest Match Results \n\n" . $userMatches,
            ],
        ];

        return $output;
    }
}
