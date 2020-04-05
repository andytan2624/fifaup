<?php

namespace App\Actions\Match;

use App\Actions\ActionInterface;
use App\League;
use App\Match;
use App\ScoreType;

class ValidateTransformedMatchDataAction implements ActionInterface
{

    private $league;
    private $data;

    private const MAX_SCORE = 1000;

    /**
     * ValidateTransformedMatchDataAction constructor.
     * @param League $league
     * @param array $data
     */
    public function __construct(League $league, array $data)
    {
        $this->league = $league;
        $this->data = $data;
    }

    /**
     * @return string|null
     */
    public function execute(): ?string
    {
        if ($this->league->getScoreTypeCode() === ScoreType::RUMBLE) {
            return $this->executeRumbleValidation();
        }

        $errorMessage = null;

        // Check if the same user appears in both teams, if so, throw an error
        $commonUsers = array_intersect($this->data[1]['users'], $this->data[2]['users']);
        if (count($commonUsers) > 0) {
            $errorMessage = "The same user cannot be versing each other";
        }

        if (count($this->data[1]['users']) === 0 || count($this->data[2]['users']) === 0) {
            $errorMessage = "Each match must have at least one player on each team";
        }

        if ($this->data[1]['score'] > self::MAX_SCORE || $this->data[2]['score'] > self::MAX_SCORE) {
            $errorMessage = "No score can be more than " . self::MAX_SCORE;
        }

        return $errorMessage;
    }

    /**
     * @return array
     */
    public function executeRumbleValidation() :?string
    {
        $errorMessage = null;

        if (count($this->data['users']) !== count(array_unique($this->data['users']))) {
            $errorMessage = "There cannot be duplicate users in the list";
        }

        return $errorMessage;
    }
}
