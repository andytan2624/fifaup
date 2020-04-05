<?php

namespace App\Http\Controllers;

use App\Actions\League\ProcessMatchResultForLeagueAction;
use App\Actions\League\RetrieveLeagueRecentMatchesAction;
use App\Actions\League\RetrieveLeagueScoreboardAction;
use App\Actions\Match\ValidateTransformedMatchDataAction;
use App\Actions\Slack\GetSlackPrettyHelpAction;
use App\Actions\Slack\GetSlackPrettyLadderAction;
use App\Actions\Slack\GetSlackPrettyMatchResultAction;
use App\Actions\Slack\GetSlackPrettyRecentResultsAction;
use App\Actions\Slack\PrintPrettySlackErrorMessageAction;
use App\Actions\Slack\TransformSlackMatchRequest;
use App\League;
use App\Organization;
use App\ScoreType;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Request;

class IncomingController extends Controller
{
    public const HOOK_NEW = 'new';
    public const HOOK_LADDER = 'ladder';
    public const HOOK_RECENT = 'recent';
    public const HOOK_HELP = 'help';

    public function test(Request $request)
    {
        return view('test');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $input = $request->all();

        $channel_id = $input['channel_id'];
        $league = League::where('slack_channel_id', $channel_id)->first();
        $team_id = $input['team_id'];
        $organization = Organization::where('slack_team_id', $team_id)->first();

        $textArray = explode(' ', $input['text']);

        $command = array_shift($textArray);

        $response = $this->getHelpBlurb($league);

        if ($command === self::HOOK_NEW) {
            $response = $this->createNewMatch($organization, $league, $textArray);
        } elseif ($command === self::HOOK_LADDER) {
            $response = $this->getLadder($league);
        } elseif ($command === self::HOOK_RECENT) {
            $response = $this->getRecentMatches($league);
        }

        return response()->json([
            "response_type" => "in_channel",
            "blocks" => $response
        ]);
    }


    /**
     * @param Organization $organization
     * @param League $league
     * @param array $textArray
     * @return array
     */
    public function createNewMatch(Organization $organization, League $league, array $textArray) :array
    {
        $teamData = (new TransformSlackMatchRequest($organization, $league, $textArray))->execute();

        // Validate data
        $errorMessage = (new ValidateTransformedMatchDataAction($league, $teamData))->execute();

        if (null !== $errorMessage) {
            return (new PrintPrettySlackErrorMessageAction($errorMessage))->execute();
        }

        if ($league->getScoreTypeCode() === ScoreType::RUMBLE) {
            $matchProcessedData = [
                'league_id' => $league->league_id,
                'users' => $teamData,
            ];
        } else {
            // Now we call the logic to record match data
            $matchProcessedData = [
                'league_id' => $league->league_id,
                'team_1' => $teamData[1],
                'team_2' => $teamData[2],
            ];
        }
        $match = (new ProcessMatchResultForLeagueAction($matchProcessedData))->execute();

        return (new GetSlackPrettyMatchResultAction($match))->execute();
    }

    /**
     * @param League $league
     * @return array
     */
    public function getLadder(League $league) :array
    {
        $retrieveLeagueScoreboardAction = new RetrieveLeagueScoreboardAction($league->league_id);
        $scores = $retrieveLeagueScoreboardAction->execute();

        return (new GetSlackPrettyLadderAction($scores))->execute();
    }

    /**
     * @param League $league
     * @return array
     */
    public function getRecentMatches(League $league) :array
    {
        $retrieveLeagueRecentMatchesAction = new RetrieveLeagueRecentMatchesAction($league->league_id);
        $matches = $retrieveLeagueRecentMatchesAction->execute();
        return (new GetSlackPrettyRecentResultsAction($league, $matches))->execute();
    }

    /**
     * @param League $league
     * @return array
     */
    public function getHelpBlurb(League $league) :array
    {
        return (new GetSlackPrettyHelpAction($league))->execute();
    }

    /**
     * authenticateSlack
     */
    public function authenticateSlack(Request $request)
    {
        $input = $request->all();

        $organization = Organization::first();

        try {
            $client = new Client(); //GuzzleHttp\Client
            $result = $client->request('POST', 'https://slack.com/api/oauth.v2.access', [
                'form_params' => [
                    'client_id' => env('SLACK_CLIENT_ID'),
                    'client_secret' => env('SLACK_CLIENT_SECRET'),
                    'code' => $input['code'],
                    'redirect_url' => env('SLACK_REDIRECT_URL')
                ]
            ]);

            $data = json_decode($result->getBody()->getContents(), true);

            if (isset($data['error']) && $data['error'] !== '') {
                die('There was an error. '. $data['error']);
            }

            if (null !== $data['team']['id']) {
                $organization->slack_team_id = $data['team']['id'];
                $organization->slack_token = $data['access_token'];
                $organization->save();
            }

            $league = null;
            // TODO: fix this so its coming from the page, based on state
            if ($input['state'] == "TT") {
                $league = League::where('name', 'Cover Genius Table Tennis League')->first();
            } elseif ($input['state'] == "FIFA") {
                $league = League::where('name', 'FIFA League')->first();
            } elseif ($input['state'] == "SCRIB") {
                $league = League::where('name', 'Cover Genius Scribbl League')->first();
            }

            if (null !== $league && null !== $data['incoming_webhook']['channel_id']) {
                $league->slack_channel_id = $data['incoming_webhook']['channel_id'];
                $league->save();
            }
        } catch (GuzzleException $e) {
            echo $e->getResponse()->getBody()->getContents();
        }
    }
}
