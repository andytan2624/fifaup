<?php

namespace App\Actions\User;

use App\Actions\ActionInterface;
use App\User;

class CreateUserAction implements ActionInterface
{

    public $data;
    public $organization;

    /**
     * CreateUserAction constructor.
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        $this->data = $data;
    }

    public function execute(): User
    {
        // Create a new match record
        $user = new User();
        $user->fill([
            'name' => $this->data['name'],
            'email' => $this->data['email'],
            'slack_user_id' => $this->data['slack_user_id'],
            'avatar_url' => $this->data['avatar_url'],
            'password' => rand(5, 15),
        ]);
        $user->save();

        return $user;
    }
}
