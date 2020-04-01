<?php

namespace App\Actions\Match;

use App\Actions\ActionInterface;
use App\Match;

class ValidateTransformedMatchDataAction implements ActionInterface
{

    private $data;

    /**
     * CreateMatchRecordAction constructor.
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * @return string|null
     */
    public function execute(): ?string
    {
        $errorMessage = null;

        // Check if the same user appears in both teams, if so, throw an error
        $commonUsers = array_intersect($this->data[1]['users'], $this->data[2]['users']);
        if (count($commonUsers) > 0) {
            $errorMessage = "The same user cannot be versing each other";
        }

        if (count($this->data[1]['users']) === 0 || count($this->data[2]['users']) === 0) {
            $errorMessage = "Each match must have at least one player on each team";
        }

        return $errorMessage;
    }
}
