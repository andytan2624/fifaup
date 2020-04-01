<?php

namespace App\Actions\Slack;

use App\Actions\ActionInterface;
use App\League;

class PrintPrettySlackErrorMessageAction implements ActionInterface
{
    private $errorMessage;

    /**
     * PrintPrettySlackErrorMessageAction constructor.
     * @param string $errorMessage
     */
    public function __construct(string $errorMessage)
    {
        $this->errorMessage = $errorMessage;
    }

    public function execute()
    {
        $output = [];

        // Title of output
        $output[] =  [
            "type" => "section",
            "text" => [
                "type" => "mrkdwn",
                "text" => ":dizzy_face: " . $this->errorMessage,
            ],
        ];

        return $output;
    }
}
