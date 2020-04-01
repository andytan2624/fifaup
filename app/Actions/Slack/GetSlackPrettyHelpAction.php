<?php

namespace App\Actions\Slack;

use App\Actions\ActionInterface;
use App\League;

class GetSlackPrettyHelpAction implements ActionInterface
{
    private $league;

    /**
     * GetSlackPrettyHelpAction constructor.
     * @param League $league
     */
    public function __construct(League $league)
    {
        $this->league = $league;
    }

    public function execute()
    {
        $output = [];

        $helpBlurb = "These are available commands on The Office Cup \n";

        $helpBlurb .= "- Record a match between two teams e.g. _/officecup new @playerOne 12 @playerTwo 3_ ";
        $helpBlurb .= "OR _/officecup new @playerOne @playerTwo 4 @playerThree @playerFour 3_ \n\n";
        $helpBlurb .= "- Get the latest rankings of players based on the last month of results e.g. _/officecup ladder_ \n\n";
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
