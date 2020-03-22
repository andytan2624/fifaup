<?php

namespace App\Actions\Slack;

use App\Actions\ActionInterface;

use App\Actions\User\CreateUserAction;
use App\League;
use App\Organization;
use App\User;
use Carbon\Carbon;
use GuzzleHttp\Client;

class CreateUserFromSlackId implements ActionInterface
{

    public $slackUserId;
    public $organization;

    public const SLACK_USER_INFO_URL = 'https://slack.com/api/users.info';

    /**
     * CreateUserFromSlackId constructor.
     * @param string $slackUserId
     * @param Organization $organization
     */
    public function __construct(string $slackUserId, Organization $organization)
    {
        $this->slackUserId = $slackUserId;
        $this->organization = $organization;
    }

    /**
     * @return User|null
     */
    public function execute(): ?User
    {
        try {
            $client = new Client(); //GuzzleHttp\Client
            $result = $client->request('GET', self::SLACK_USER_INFO_URL, [
                'query' => [
                    'token' => $this->organization->slack_token,
                    'user' => $this->slackUserId,
                ]
            ]);

            $data = json_decode($result->getBody()->getContents(), true);


            $userData = [
                'name' => $data['user']['profile']['display_name'] !== '' ? $data['user']['profile']['display_name'] :
                    $data['user']['profile']['real_name'],
                'email' => $data['user']['profile']['email'],
                'slack_user_id' => $this->slackUserId,
                'avatar_url' => $data['user']['profile']['image_512'],
            ];

            return (new CreateUserAction($userData))->execute();
        } catch (GuzzleException $e) {
            return null;
        }
    }
}
