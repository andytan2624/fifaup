<?php

namespace App\Actions\Slack;

use App\Actions\ActionInterface;
use App\Match;
use App\Score;

class GetSlackPrettyMatchResultAction implements ActionInterface
{
    /** @var Match $match */
    private $match;

    /**
     * GetPrettyMatchResultAction constructor.
     * @param Match $match
     */
    public function __construct(Match $match)
    {
        $this->match = $match;
    }

    public function execute()
    {
        $scores = $this->match->scores;

        $winningTeam = null;
        $losingTeam = null;
        $drawTeam = null;

        if ($this->match->isDraw()) {
            $drawTeam = [];
            /** @var Score $score */
            foreach ($this->match->drawTeam as $score) {
                $drawTeam[] = $score->user;
            }
        } else {
            $winningTeam = [];
            /** @var Score $score */
            foreach ($this->match->winningTeam as $score) {
                $winningTeam[] = $score->user;
            }

            $losingTeam = [];
            /** @var Score $score */
            foreach ($this->match->losingTeam as $score) {
                $losingTeam[] = $score->user;
            }
        }

        $output = [];

        // Title of output
        $output[] =  [
            "type" => "section",
            "text" => [
                "type" => "mrkdwn",
                "text" => "Match has been recorded successfully"
            ],
        ];

        $higherScore = $this->match->higher_score > -1 ? $this->match->higher_score : "({$this->match->higher_score})";
        $lowerScore = $this->match->lower_score > -1 ? $this->match->lower_score : "({$this->match->lower_score})";

        // Scoreline of output
        $output[] =  [
            "type" => "section",
            "text" => [
                "type" => "mrkdwn",
                "text" => "*Score: ". $higherScore ." - ". $lowerScore ."*"
            ]
        ];

        if (null !== $winningTeam) {
            $winningTeamUsers = "";
            foreach ($winningTeam as $user) {
                $winningTeamUsers .= "\n" . $user['name'];
            }

            $output[] = [
                "type" => "section",
                "text" => [
                    "type" => "mrkdwn",
                    "text" => ":first_place_medal: *Winner* " . $winningTeamUsers,
                ]
            ];
        }

        if (null !== $losingTeam) {
            $losingTeamUsers = "";
            foreach ($losingTeam as $user) {
                $losingTeamUsers .= "\n" . $user['name'];
            }

            $output[] = [
                "type" => "section",
                "text" => [
                    "type" => "mrkdwn",
                    "text" => ":broken_heart: *Loser* " . $losingTeamUsers,
                ]
            ];
        }

        if (null !== $drawTeam) {
            $drawTeamUsers = "";
            foreach ($drawTeam as $user) {
                $drawTeamUsers .= "\n" . $user['name'];
            }

            $output[] = [
                "type" => "section",
                "text" => [
                    "type" => "mrkdwn",
                    "text" => ":scales: *Draw* " . $drawTeamUsers,
                ]
            ];
        }

        return $output;
    }
}
