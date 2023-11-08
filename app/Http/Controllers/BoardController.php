<?php

namespace App\Http\Controllers;

use App\Board;
use App\Team;
use App\Column;
use App\Card;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class BoardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::user()->role == "scrumMaster") {
            $boards = Auth::user()->boards;
        }else{
            $boards = Auth::user()->team->boards->whereIn('status',['ready','frozen']);
        }
        $columnsFinal = config('constants.columns');
        foreach ($boards as $board) {
            $date = Carbon::parse($board->created_at);
            $board->createdAtDisplayDate = $date->format("j M Y");
            $columnsFinal = config('constants.columns');
            $maxValue = 0;
            $maxType = "";
            foreach ($board->columns as $key => $column) {
                $count = count($column->cards);
                if ($count > $maxValue) {
                    $maxType = $column->type;
                    $maxValue = $count;
                }
                $columnsFinal[$column->type]["count"] = $count;
            }
            foreach ($columnsFinal as $key => $column) {
                if ($maxValue == 0) {
                    $columnsFinal[$key]['percentile'] = 0;
                }else{
                    $columnsFinal[$key]['percentile'] = round(($columnsFinal[$key]['count']/$maxValue) * 100, 2);
                }
            }
            $board->columnsFinal = $columnsFinal;
        }

        return view('boards.index', compact('boards','columnsFinal'));
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
        $board = new Board();

        $team = Team::where('uuid', $request->team_id)->firstOrFail();

        $board->user_id = Auth::id();
        $board->team_id = $team->id;
        $board->uuid = Str::uuid();
        $board->name = $request->name;
        $board->description = $request->description;

        $board->save();

        $columnNames = config('constants.columnNames');
        foreach ($columnNames as $key => $columnName) {
            $column = new Column();
            $column->board_id = $board->id;
            $column->uuid = Str::uuid();
            $column->order = $key + 1;
            $column->type = $columnName;
            $column->name = config("constants.columns.$columnName.name");
            $column->color = config("constants.columns.$columnName.color");
            $column->save();
        }

        session()->flash('success', 'Board created successfully');

        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Boards  $boards
     * @return \Illuminate\Http\Response
     */
    public function show($uuid)
    {
        if (Auth::user()->role == 'scrumMaster') {
            $board = Board::where('uuid', $uuid)->firstOrFail();
        }else{
            $board = Board::where('uuid', $uuid)->whereIn('status',['ready','frozen'])->firstOrFail();
        }

        return view('boards.boardDetails', compact('board'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Boards  $boards
     * @return \Illuminate\Http\Response
     */
    public function edit($uuid)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Boards  $boards
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $uuid)
    {
        $board = Board::where('uuid',$uuid)->firstOrFail();

        $board->name = $request->name;
        $board->description = $request->description;
        $board->status = $request->status;

        $board->save();

        session()->flash('success', 'Board updated successfully');

        return back();
    }

    public function destroy($uuid)
    {
        $auth_user = Auth::user();

        $board = Board::where('uuid',$uuid)->firstOrFail();
        if ($auth_user->id == $board->user_id) {
            foreach ($board->columns as $column) {
                foreach ($column->cards as $card) {
                    $users = $card->likes;
                    foreach ($users as $user) {
                        $user->likedCard()->detach($card);
                    }
                    $card->delete();
                }
                $column->delete();
            }
            $board->delete();
            session()->flash('success', 'Board was deleted successfully');
            return back();
        }else{
            $response = [
                "status" => "error",
                "message" => "User not authorised to delete the board.",
            ];
        }
        return response()->json($response);
    }

    public function storeCard(Request $request){
        \Log::info("came here!");
        $column = Column::where("uuid", $request->column_id)->firstOrFail();

        $card = new Card;

        $card->user_id = Auth::id();
        $card->column_id = $column->id;
        $card->uuid = Str::uuid();
        $card->content = $request->content;
        $card->color = $column->color;
        $card->save();
        
        $card->author_name = $card->author->name;
        $card->type = $column->type;

        $response = [
            "status" => "success",
            "message" => "Card created successfully",
            "data" => $card->toArray(),
        ];

        return response()->json($response);
    }

    public function likeCard(Request $request){
        $card = Card::where("uuid", $request->card_id)->firstOrFail();
        $user = Auth::user();
        
        $response = [
            "status" => "success",
            "message" => "Like status updated successfully",
            "data" => "",
        ];
        if ($request->status == "liked" || $user->likedCards->contains($card)) {
            $user->likedCards()->detach($card);
            $response['data'] = 'disliked';
        }else{
            $user->likedCards()->attach($card);
            $response['data'] = 'liked';
        }

        return response()->json($response);
    }

    public function destroyCard(Request $request){
        $card = Card::where("uuid", $request->card_id)->firstOrFail();
        $auth_user = Auth::user();
        
        if ($auth_user->id == $card->user_id || $auth_user->id == $card->column->board()->user_id) {
            $users = $card->likes;
            foreach ($users as $user) {
                $user->likedCard()->detach($card);
            }
            $card->delete();
            $response = [
                "status" => "success",
                "message" => "Card was deleted successfully.",
            ];
        }else{
            $response = [
                "status" => "error",
                "message" => "User not authorised to delete.",
            ];
        }
        return response()->json($response);
    }

    public function moveCard(Request $request){
        $card = Card::where("uuid", $request->card_id)->firstOrFail();
        $auth_user = Auth::user();
        $board = Board::findOrFail($card->column->board_id);

        $actionItemColumn = $board->columns->where("type", 'actionItem')->first();
        
        if ($auth_user->id == $board->user_id) {
            $card->column_id = $actionItemColumn->id;
            $card->color = $actionItemColumn->color;

            $card->save();

            $card->author_name = $card->author->name;
            $card->likes_count = $card->likes->count();
            $card->liked = (string)$auth_user->likedCards->contains($card);
            $response = [
                "status" => "success",
                "message" => "Card updated successfully",
                "data" => $card->toArray(),
            ];
        }else{
            $response = [
                "status" => "error",
                "message" => "User not authorised to move the card.",
            ];
        }
        return response()->json($response);
    }
}
