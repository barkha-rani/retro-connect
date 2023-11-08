<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\User;
use App\Card;
use App\Board;

class TempController extends Controller
{
    public function baseRedirect(){
        return redirect()->route('dashboard');
    }

    public function dashboard()
    {
        $team_ids = Auth::user()->teams->pluck('id')->toArray();
        $membersCount = User::whereIn('team_id',$team_ids)->count();
        $user_ids = User::whereIn('team_id',$team_ids)->pluck('id')->toArray();
        if (Auth::user()->role == 'scrumMaster') {
            array_push($user_ids, Auth::user()->id);
        }
        $cardsCount = Card::whereIn('user_id', $user_ids)->count();
        $lineChartData = [];
        $boards = Board::whereIn('team_id', $team_ids)->orderBy('created_at', 'asc')->take(10)->get();
        $board_names = [];
        $board_stats = [];
        foreach ($boards as $board) {
            $board_names[] = $board->name;
            foreach ($board->columns as $column) {
                $board_stats[$column->type]['color'] = config('constants.colorDetails.'.$column->color.'.hex');
                if (empty($board_stats[$column->type])) {
                    $board_stats[$column->type]['values'] = array($column->cards->count());
                }else{
                    $board_stats[$column->type]['values'][] = $column->cards->count();
                } 
            }
        }
        $lineChartData['board_names'] = $board_names;
        $lineChartData['board_stats'] = $board_stats;
        \Log::info($lineChartData);
        return view('dashboard', compact('membersCount','cardsCount', 'lineChartData'));
    }
    
    public function teams()
    {
        return view('teams');
    }
    public function teamDetails()
    {
        return view('teamDetails');
    }
    public function boardDetails()
    {
        return view('boardDetails');
    }
}
