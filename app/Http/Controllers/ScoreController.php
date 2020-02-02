<?php

namespace App\Http\Controllers;

use App\Actions\League\RetrieveLeagueScoreboardAction;
use Illuminate\Http\Request;

class ScoreController extends Controller
{
    /**
     * Get the scoreboard of all the players in a league passed by a parameter
     */
    public function index(Request $request)
    {
        $data = $request->all();
        // Validate data here

        $retrieveLeagueScoreboardAction = new RetrieveLeagueScoreboardAction($data['league_id']);
        $scores = $retrieveLeagueScoreboardAction->execute();
        return response()->json(['data' => $scores], 200);
    }


}
