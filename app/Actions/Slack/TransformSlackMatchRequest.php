<?php

namespace App\Actions\Slack;

use App\Actions\ActionInterface;

use App\League;
use App\Organization;
use App\Score;
use Carbon\Carbon;

class TransformSlackMatchRequest implements ActionInterface
{
    /**
     * @var Organization $organization
     */
    public $organization;
    /**
     * @var League $league
     */
    public $league;
    /**
     * @var array $data
     */
    public $data;

    /**
     * TransformSlackMatchRequest constructor.
     * @param Organization $organization
     * @param League $league
     * @param array $data
     */
    public function __construct(Organization $organization, League $league, array $data = [])
    {
        $this->organization = $organization;
        $this->league = $league;
        $this->data = $data;
    }

    public function execute(): array
    {
        $teamData = [
            1 => [
                'users' => [],
                'status' => Score::STATUS_DRAW,
                'score' => null,
            ],
            2 => [
                'users' => [],
                'status' => Score::STATUS_DRAW,
                'score' => null,
            ],
        ];

        // Determine if we are collecting data for team 1 or team 2
        $switch = 1;

        foreach ($this->data as $data) {
            preg_match("/<@(\w+)|/", $data, $user);

            if (count($user) == 2) {
                $userSlackId = $user[1];
                $userId = (new RetrieveUserIdFromSlackId($userSlackId, $this->organization, $this->league))->execute();
                $teamData[$switch]['users'][] = $userId;
            }

            if (count($user) < 2 && is_int((int) $data)) {
                $teamData[$switch]['score'] = (int) $data;
                if (1 === $switch) {
                    $switch = 2;
                }
            }
        }

        if ($teamData[1]['score'] > $teamData[2]['score']) {
            $teamData[1]['status'] = Score::STATUS_WIN;
            $teamData[2]['status'] = Score::STATUS_LOSS;
        } elseif ($teamData[2]['score'] > $teamData[1]['score']) {
            $teamData[2]['status'] = Score::STATUS_WIN;
            $teamData[1]['status'] = Score::STATUS_LOSS;
        }

        return $teamData;
    }
}
