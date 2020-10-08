<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\File;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\TicketRequest;

class TicketController extends Controller
{
    public function getAllTickets($order = 'desc')
    {
        if($order == 'desc' or $order == 'asc'){
            return response()->json(DB::table('tickets')
                ->join('files', function($join) {
                    $join->on('files.id', '=',
                    DB::raw('(SELECT id FROM files
                    WHERE ticket_id = tickets.id
                    ORDER BY created_at DESC
                    LIMIT 1)'));
                })
                ->select('tickets.created_at', 'tickets.title', 'tickets.author_tel', 'tickets.status', 'files.path as file')
                ->orderBy('created_at', $order)
                ->simplePaginate(10),
                200);
        } else {
            return response()->json(['Error' => 'Wrong order'], 400);
        }
    }


    public function showTicket($id, $fields = null)
    {
        $status = Ticket::find($id);
        if($status){
            if(isset($fields) and $fields == 'fields'){
                $status = Ticket::find($id);
                $ticket = Ticket::where('id', $id)->with('files')->get();
                if($status->status == 'open'){
                   $ticket = $ticket->makeHidden('updated_at');
                }
                return response()->json($ticket, 200);
            }
            else {
                return response()->json(Ticket::where('id', $id)
                ->select('title', 'created_at', 'status', 'text', 'author_tel')
                ->get(), 200);
            }
        } else {
            return response()->json(['Error' => 'Not found'], 404);
        }
    }


    public function store(TicketRequest $request)
    {
        $ticket = Ticket::create([
            'title' => $request->title,
            'text' => $request->text,
            'author_name' => $request->author_name,
            'author_tel' => $request->author_tel,
            'status' => 'open',
        ]);
        if($request->hasFile('file')){
            foreach($request->file as $file){
                $path = $file->store('files');
                $fileUpload = File::create([
                    'path' => $path,
                    'ticket_id' => $ticket->id
                ]);
            }
        }
        return response()->json($ticket->id, 200);
    }


    public function newStatus($id, $newStatus)
    {
        if($newStatus == 'open' or $newStatus == 'close'){
            $ticket = Ticket::find($id);
            if($ticket->status == 'close'){
                $closeDate = strtotime($ticket->updated_at);
                $currentDate = time();
                $diff = ($currentDate - $closeDate)/60/60/24;
                if($diff > 7){
                    return response()->json(['Error' => 'Timeout'], 423);
                }
            }
            $ticket->status = $newStatus;
            $ticket->save();

        return response()->json('Success', 200);

        } else {
            return response()->json(['Error' => 'Wrong new status'], 400);
        }
    }
}
