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

    /**
     * RetrieveUserIdFromSlackId constructor.
     * @param string $slackUserId
     * @param Organization $organization
     */
    public function __construct(string $slackUserId, Organization $organization)
    {
        $this->slackUserId = $slackUserId;
        $this->organization = $organization;
    }

    public function execute(): string
    {
        $user = User::where('slack_user_id', $this->slackUserId)->first();
        if (!$user) {
            // Retrieve from slack API then
            $user = (new CreateUserFromSlackId($this->slackUserId, $this->organization))->execute();
        }

        return $user->user_id;
    }
}
