<?php
namespace App\Actions\Score;

use App\Actions\ActionInterface;
use App\Match;
use App\Score;
use App\User;

class CreateScoreRecordAction implements ActionInterface
{
    private $match;
    private $data;
    private $team;

    /**
     * CreateScoreRecordAction constructor.
     * @param Match $match
     * @param array $data
     * @param string $team
     */
    public function __construct(Match $match, array $data, string $team = null)
    {
        $this->match = $match;
        $this->data = $data;
        $this->team = $team;
    }

    public function execute(): void
    {

        // TODO: we need some validation on the data here

        // Iterate over every user in the users array
        foreach ($this->data['users'] as $index => $user) {
            // Check if $user is an email cause then we need to get an ID
            if (filter_var($user, FILTER_VALIDATE_EMAIL)) {
                // valid address
                $user = User::where('email', $user)->first()->user_id;
            }

            // Create a new match record
            $score = new Score();

            $scoreData = [
                'match_id' => $this->match->getKey(),
                'user_id' => $user,
            ];

            if ($this->team != null) {
                $scoreData['points'] = $this->data['score'];
                $scoreData['status'] = $this->data['status'];
                $scoreData['team'] = $this->team;
                $scoreData['is_team'] = count($this->data['users']) > 1 ? 1 : 0;
            } else {
                $scoreData['points'] = count($this->data['users']) - $index;
                $scoreData['rank_placement'] = ($index + 1);
            }

            $score->fill($scoreData);
            $score->save();
        }
    }
}
