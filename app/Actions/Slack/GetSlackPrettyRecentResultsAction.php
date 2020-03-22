<?php

namespace App\Actions\Slack;

use App\Actions\ActionInterface;

class GetSlackPrettyRecentResultsAction implements ActionInterface
{
    private $data;

    /**
     * GetSlackPrettyLadderAction constructor.
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function execute()
    {
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
}
