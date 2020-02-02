<?php

namespace App\Http\Controllers;

use App\Actions\League\RetrieveLeagueRecentMatchesAction;
use Illuminate\Http\Request;

class LeagueController extends Controller
{
    public function latestMatches(Request $request, string $league_id = '') {
        $data = $request->all();

        $retrieveLeagueRecentMatchesAction = new RetrieveLeagueRecentMatchesAction($league_id);
        $matches = $retrieveLeagueRecentMatchesAction->execute();
        return response()->json(['data' => $matches], 200);
    }
}
