<?php

namespace App\Http\Controllers;

use App\Models\TicketRaising;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketRaisingController extends Controller
{
     public function index(Request $request)
    {

        if ($request->ajax()) {

            $user_id = Auth::user()->id;
            $tickets = TicketRaising::with('ticket_raiser','ticketDepartment')
             ->when(auth()->user()->hasAnyRole(['G5','G4']), function ($query) use ($user_id) {
                        $query->where('user', $user_id);
                    })
            ->get()
            ->map(function ($tickets) {
                return [
                    'id' => $tickets->id,
                    'employee' => $tickets->ticket_raiser ? $tickets->ticket_raiser->full_name : '',
                    'ticket_department' => $tickets->ticketDepartment? $tickets->ticketDepartment->department : '',
                    'ticket_title' => $tickets->ticket_title ? $tickets->ticket_title : '',
                    'ticket_description' => $tickets->ticket_description ? $tickets->ticket_description  : '',
                    'issue_date_time' => $tickets->issue_date_time ? $tickets->issue_date_time : '',
                    'close_date_time' => $tickets->close_date_time ? $tickets->close_date_time : '',
                    'picture' => $tickets->picture ? $tickets->picture : '',
                    'status' => $tickets->status ? $tickets->status : '',
                    'created_at' => $tickets->created_at ? date('d-m-Y', strtotime($tickets->created_at)) : '',
                ];
            });

            return response()->json([
                'data' => $tickets
            ]);

        }

        $data['meta_title'] = 'Tickets';
        return view('ticket-raising.index', $data);
    }


    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        $user = Auth::user();
        $profileImagePath = null;
        if ($request->hasFile('picture')) {
            $file = $request->file('picture');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $profileImagePath = $file->storeAs('ticket_pictures', $filename, 'public');
        }

        $tickets = TicketRaising::updateOrCreate(
            ['id' => $request->id], // 🔍 Match condition (unique identifier)
            [
                'user' => $user->id,
                'ticket_department' => $user->employee? $user->employee->department_id : '',
                'ticket_title' => $request->ticket_title,
                'ticket_description' => $request->ticket_description,
                'issue_date_time' => date('Y-m-d H:i:s'),
                'picture' => $profileImagePath ?? ($request->id ? TicketRaising::find($request->id)->picture : 'tickets_pictures/no-images.jpg'),
            ]
        );

        if ($profileImagePath) {
            $tickets->save();
        }
        return redirect()->back()->with('success', 'Tickets created successfully!');
    }

    public function show(TicketRaising $ticketRaising)
    {
        //
    }


    public function edit($id){
        $tickets = TicketRaising::find($id);
        $data['tickets'] = $tickets;
        return response()->json($data);
    }


    public function update(Request $request, TicketRaising $ticketRaising)
    {
        //
    }

   public function destroy($id)
    {
        $tickets = TicketRaising::find($id);
        if ($tickets->picture && $tickets->picture !== 'tickets_pictures/no-images.jpeg') {
            // Check if file exists in storage
            $imagePath = storage_path('app/public/' . $tickets->picture);
            if (file_exists($imagePath)) {
                unlink($imagePath); // Delete the file
            }
        }
        $tickets->delete();
        return response()->json(['message' => 'Tickets deleted successfully']);
    }

    public function close(Request $request, $id)
    {
        $ticket = TicketRaising::findOrFail($id);

        $ticket->status = 1;
        $ticket->comment = $request->comment;
        $ticket->close_date_time = date('Y-m-d H:i:s'); // optional
        $ticket->save();

        return response()->json(['message' => 'Ticket closed successfully']);
    }
}
