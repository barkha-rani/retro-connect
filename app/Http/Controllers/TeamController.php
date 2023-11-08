<?php

namespace App\Http\Controllers;

use App\Team;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class TeamController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();

        $teams = $user->teams;

        return view('teams')->with('teams', $teams);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $team = new Team();

        $team->name = $request->name;
        $team->description = $request->description;
        $team->user_id = auth()->user()->id;
        $team->uuid = Str::uuid();

        $team->save();

        session()->flash('success', 'Team created successfully');

        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Team  $team
     * @return \Illuminate\Http\Response
     */
    public function show($uuid)
    {
        $team = Team::where('uuid', $uuid)->first();

        return view('teamDetails')->with('team', $team);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Team  $team
     * @return \Illuminate\Http\Response
     */
    public function edit(Team $team)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Team  $team
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $uuid)
    {
        $team = Team::where('uuid', $uuid)->firstOrFail();

        $team->name = $request->name;
        $team->description = $request->description;

        $team->save();

        session()->flash('success', 'Team updated successfully');
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Team  $team
     * @return \Illuminate\Http\Response
     */
    public function destroy($uuid)
    {
        $team = Team::where('uuid', $uuid)->firstOrFail();
        
        foreach ($team->members as $teamMember) {
            $teamMember->team_id = NULL;
            $teamMember->save();
        }

        $team->delete();
        return redirect()->route('teams.index')->with('success', 'Team deleted successfully');
    }

    public function myTeam(){
        $team = Auth::user()->team;
        return view('teamDetails')->with('team', $team);
    }

    public function addMember(Request $request){
        $team = Team::where('uuid', $request->team_id)->firstOrFail();
        $user = User::where('email', $request->email)->firstOrFail();

        $user->team_id = $team->id;
        $user->save();

        session()->flash('success', 'Team member added successfully');
        return back();
    }
}
