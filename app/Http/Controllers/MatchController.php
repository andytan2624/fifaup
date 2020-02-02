<?php

namespace App\Http\Controllers;

use App\Score;
use Illuminate\Http\Request;
use App\Actions\Match\CreateMatchRecordAction;
use App\Actions\Score\CreateScoreRecordAction;

class MatchController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        /**
         * Get the data
         * find all the users
         * get the league
         * create action class
         * to create a match record with the team score
         *
         * then we create score records, two of them
         */
        // TODO: we need some validation on the data here

        $data = $request->all();
        $createMatchRecordAction = new CreateMatchRecordAction($data);
        $match = $createMatchRecordAction->execute();

        $createTeam1ScoreRecord = new CreateScoreRecordAction($match, $data['team_1'], Score::TEAM_1);
        $createTeam1ScoreRecord->execute();

        $createTeam2ScoreRecord = new CreateScoreRecordAction($match, $data['team_2'], Score::TEAM_2);
        $createTeam2ScoreRecord->execute();

        return response()->json(['data' => $match->toArray()], 201);
    }
}
