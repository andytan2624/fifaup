<?php

namespace App\Actions\Slack;

use App\Actions\ActionInterface;

class GetSlackPrettyLadderAction implements ActionInterface
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

        $userRankings = "";

        foreach ($this->data as $index => $userData) {
            $userRankings .= ($index + 1) . ". $userData[user] - $userData[points]\n";
        }
        // Title of output
        $output[] =  [
            "type" => "section",
            "text" => [
                "type" => "mrkdwn",
                "text" => ":trophy: Championship Rankings \n" . $userRankings,
            ],
        ];

        return $output;
    }
}
