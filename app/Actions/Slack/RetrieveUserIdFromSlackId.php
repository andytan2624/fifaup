<?php

namespace App\Actions\Slack;

use App\Actions\ActionInterface;

use App\League;
use App\Organization;
use App\User;

class RetrieveUserIdFromSlackId implements ActionInterface
{

    public $slackUserId;
    public $organization;
    public $league;

    /**
     * RetrieveUserIdFromSlackId constructor.
     * @param string $slackUserId
     * @param Organization $organization
     * @param League $league
     */
    public function __construct(string $slackUserId, Organization $organization, League $league)
    {
        $this->slackUserId = $slackUserId;
        $this->organization = $organization;
        $this->league = $league;
    }

    public function execute(): string
    {
        $user = User::where('slack_user_id', $this->slackUserId)->first();
        if (!$user) {
            // Retrieve from slack API then
            $user = (new CreateUserFromSlackId($this->slackUserId, $this->organization))->execute();

            // Now let's add this user to the organization and league as well
            $user->leagues()->sync($this->league);
            $user->organizations()->sync($this->organization);
        }

        return $user->user_id;
    }
}
