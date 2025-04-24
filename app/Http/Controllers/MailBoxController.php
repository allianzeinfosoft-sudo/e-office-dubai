<?php

namespace App\Http\Controllers;

use App\Models\MailBox;
use App\Models\Employee;
use Illuminate\Http\Request;

class MailBoxController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if($request->ajax()) {
            $mails = MailBox::orderBy('created_at', 'desc')->get();

            return response()->json([
                'status' => true,
                'data' => $mails
            ]);
        }
        //
        $data['meta_title'] = 'Email';
        $data['employees'] = Employee::all();
        return view('mailBox.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request){
        $request->validate([
            'to_user_ids' => 'required|json',
            'cc_user_ids' => 'nullable|json',
            'bcc_user_ids' => 'nullable|json',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        $mail = new MailBox();
        $mail->from_user_id = auth()->id();
        $mail->to_user_ids = json_decode($request->to_user_ids, true);
        $mail->cc_user_ids = $request->cc_user_ids ? json_decode($request->cc_user_ids, true) : [];
        $mail->bcc_user_ids = $request->bcc_user_ids ? json_decode($request->bcc_user_ids, true) : [];
        $mail->subject = $request->subject;
        $mail->message = $request->message;
        $mail->status = $request->status ?? 0;
    
        
        // Assign folder name based on status
        if ($mail->status >= 0 && $mail->status <= 7) {
            $folders = MailBox::folders();
            $mail->folder = $folders[$mail->status] ?? 'inbox';  // Fallback to inbox
        }
        
        // Handling multiple attachments
        if ($request->hasFile('attachments')) {
            $files = [];
            
            foreach ($request->file('attachments') as $file) {
                $fileName = $file->hashName(); // Get unique file name
                $file->storeAs('mail_attachments', $fileName, 'public'); // Store using custom name
                $files[] = $fileName;
            }

            $mail->attachments = json_encode($files); // Save as JSON array
        }
        $mail->save();

        return response()->json(['status' => true, 'message' => 'Mail saved successfully!']);
    }


    /**
     * Display the specified resource.
     */
    public function show(MailBox $mailBox)
    {
        //
        $data['mail'] = $mailBox;

        if (!$data['mail']) {
            return response()->json([
                'status' => false,
                'message' => 'Mail not found.'
            ], 404);
        }

        $html = view('mailBox.readEmail', $data)->render();

        return response()->json([
            'status' => true,
            'html' => $html,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MailBox $mailBox)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MailBox $mailBox)
    {
        //
        $mail = MailBox::find($id);

        if (!$mail) {
            return response()->json([
                'status' => false,
                'message' => 'Mail not found.'
            ], 404);
        }

        $mail->update($request->only([
            'to_user_ids', 'cc_user_ids', 'bcc_user_ids',
            'subject', 'message', 'attachments', 'status', 'folder'
        ]));

        return response()->json([
            'status' => true,
            'message' => 'Mail updated successfully.',
            'data' => $mail
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MailBox $mailBox)
    {
        //
        $mail = MailBox::find($id);

        if (!$mail) {
            return response()->json([
                'status' => false,
                'message' => 'Mail not found.'
            ], 404);
        }

        $mail->delete();

        return response()->json([
            'status' => true,
            'message' => 'Mail deleted successfully.'
        ]);
    }

    public function folder($folder){

        $allowedFolders = ['inbox', 'draft', 'sent', 'starred',  'spam', 'trash'];

        if (!in_array($folder, $allowedFolders)) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid folder specified.'
            ], 400);
        }

        $userId = auth()->id();

        $query = MailBox::with('fromUser');

        switch ($folder) {
            case 'inbox':
                $query->whereJsonContains('to_user_ids', (string) $userId)
                ->where('folder', 'sent');
                break;

            case 'sent':
                $query->where('from_user_id', $userId)
                    ->where('folder', 'sent');
                break;

            case 'draft':
                $query->where('from_user_id', $userId)
                    ->where('folder', 'draft');
                break;

            case 'starred':
                $query->where('is_starred', $userId)
                    ->where('folder', 'sent');
                break;
            case 'spam':
            case 'trash':
                // assuming the folder field is set for these too
                $query->where(function($q) use ($userId) {
                    $q->where('from_user_id', $userId)
                    ->orWhereJsonContains('to_user_ids', (string) $userId);
                })->where('folder', $folder);
                break;
        }

        $mails = $query->orderBy('created_at', 'desc')->get();
        
        return response()->json([
            'status' => true,
            'folder' => $folder,
            'data' => $mails
        ]);
    }

    public function starred(){
        $mails = MailBox::where('is_starred', true)
                    ->orderBy('created_at', 'desc')
                    ->get();

        return response()->json([
            'status' => true,
            'folder' => 'starred',
            'data' => $mails
        ]);
    }

    public function markAsStarred(Request $request){
        $request->validate([
            'mailId' => 'required|integer|exists:mail_boxes,id',
        ]);
    
        $mail = MailBox::find($request->mailId);
    
        // Toggle is_starred value
        $mail->is_starred = $mail->is_starred ? 0 : 1;
        $mail->save();
    
        return response()->json([
            'status' => 'success',
            'message' => 'Mail star status updated.',
            'is_starred' => $mail->is_starred ? 'text-warning': "",
            'mailId' => $mail->id
        ]);
    }
    public function moveToFolder(Request $request){
        $validated = $request->validate([
            'mailIds' => 'required|array',
            'folder' => 'required|string'
        ]);

        MailBox::whereIn('id', $validated['mailIds'])->update([
            'folder' => $validated['folder']
        ]);

        return response()->json(['status' => 'success']);
    }
}
