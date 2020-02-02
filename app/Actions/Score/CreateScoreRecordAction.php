<?php
namespace App\Actions\Score;

use App\Actions\ActionInterface;
use App\Match;
use App\Score;
use App\User;

class CreateScoreRecordAction implements ActionInterface {

    private $match;
    private $data;
    private $team;

    /**
     * CreateScoreRecordAction constructor.
     * @param Match $match
     * @param array $data
     * @param string $team
     */
    public function __construct(Match $match, array $data, string $team)
    {
        $this->match = $match;
        $this->data = $data;
        $this->team = $team;
    }

    public function execute(): void
    {
        // TODO: we need some validation on the data here

        // Iterate over every user in the users array
        foreach ($this->data['users'] as $user) {
            // Check if $user is an email cause then we need to get an ID
            if(filter_var($user, FILTER_VALIDATE_EMAIL)) {
                // valid address
                $user = User::where('email', $user)->first()->user_id;
            }

            // Create a new match record
            $score = new Score();
            $score->fill([
                'match_id' => $this->match->match_id,
                'user_id' => $user,
                'points' => $this->data['score'],
                'status' => $this->data['status'],
                'team' => $this->team,
                'is_team' => count($this->data['users']) > 1 ? 1 : 0,
            ]);
            $score->save();
        }

    }
}
