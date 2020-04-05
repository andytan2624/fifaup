<?php

namespace App\Actions\Slack;

use App\Actions\ActionInterface;
use App\League;
use App\ScoreType;
use App\Sport;

class GetSlackPrettyHelpAction implements ActionInterface
{
    private $league;
    private $scoreTypeCode;

    /**
     * GetSlackPrettyHelpAction constructor.
     * @param League $league
     */
    public function __construct(League $league)
    {
        $this->league = $league;
        $this->scoreTypeCode = $league->getScoreTypeCode();
    }

    public function execute()
    {
        $output = [];

        $helpBlurb = "These are the available commands on The Office Cup \n";

        if ($this->scoreTypeCode === ScoreType::VERSUS) {
            $helpBlurb .= "- Record a match between two teams e.g. _/officecup new @playerOne 12 @playerTwo 3_ ";
            $helpBlurb .= "OR _/officecup new @playerOne @playerTwo 4 @playerThree @playerFour 3_ \n\n";
        } elseif ($this->scoreTypeCode === ScoreType::RUMBLE) {
            $helpBlurb .= "- Record a match by listing participating players by their final rankings e.g. ";
            $helpBlurb .= " _/officecup new @playerOne @playerTwo @playerThree @playerFour_ \n\n";
        }

        $helpBlurb .= "- Get the latest rankings of players based on the last month of results " .
            "e.g. _/officecup ladder_ \n\n";
        $helpBlurb .= "-See the last twenty matches that have been played in the league e.g. _/officecup recent_";

        // Title of output
        $output[] =  [
            "type" => "section",
            "text" => [
                "type" => "mrkdwn",
                "text" => $helpBlurb,
            ],
        ];

        return $output;
    }
}
