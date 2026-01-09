<?php

namespace App\Http\Controllers;

use App\Models\MailBox;
use App\Models\Employee;
use App\Models\User;
use App\Models\EmailConfiguration;
use Illuminate\Http\Request;
use App\Helpers\CustomHelper;




class MailBoxController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    /* public function index(Request $request)
    {
        if($request->ajax()) {
            $mails = MailBox::with(['fromUser', 'userData'])
            ->orderBy('created_at', 'desc')
            ->get();

            return response()->json([
                'status' => true,
                'data' => $mails
            ]);
        }
        //
        $userId = auth()->id();

        $counts = [
            'inbox' => MailBox::whereJsonContains('to_user_ids', (string) $userId)
                            ->where('folder', 'sent')->count(),
            'sent' => MailBox::where('from_user_id', $userId)
                            ->where('folder', 'sent')->count(),
            'draft' => MailBox::where('from_user_id', $userId)
                            ->where('folder', 'draft')->count(),
            'spam' => MailBox::where(function($q) use ($userId) {
                            $q->where('from_user_id', $userId)
                            ->orWhereJsonContains('to_user_ids', (string) $userId);
                        })->where('folder', 'spam')->count(),
            'trash' => MailBox::where(function($q) use ($userId) {
                            $q->where('from_user_id', $userId)
                            ->orWhereJsonContains('to_user_ids', (string) $userId);
                        })->where('folder', 'trash')->count(),
            'starred' => MailBox::where(['from_user_id'=> $userId, 'is_starred' => 1])->count(),
        ];
        $data['meta_title'] = 'Email';
        $data['employees'] = Employee::with('user')->get();
        $data['counts'] = $counts;
        return view('mailBox.index', $data);
    } */

    public function index(Request $request)
    {
        /** --------------------------------------------------------------
         * AJAX Request — Return only stored DB emails (no external fetch)
         * --------------------------------------------------------------
         */
        if ($request->ajax()) {
            $mails = MailBox::with(['fromUser', 'userData'])
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'status' => true,
                'data'   => $mails
            ]);
        }

        $user     = auth()->user();
        $userId   = $user->id;

        /** --------------------------------------------------------------
         * Count Internal Emails for Sidebar
         * -------------------------------------------------------------- */
        $counts = [
            'inbox'   => MailBox::where(['owner_id' => $userId, 'folder' => 'inbox'])->count(),

            'sent'    => MailBox::where('from_user_id', $userId)
                            ->where('folder', 'sent')->count(),

            'draft'   => MailBox::where('from_user_id', $userId)
                            ->where('folder', 'draft')->count(),

            'spam'    => MailBox::where(function ($q) use ($userId) {
                                $q->where('from_user_id', $userId)
                                    ->orWhereJsonContains('to_user_ids', (string) $userId);
                            })->where('folder', 'spam')->count(),

            'trash'   => MailBox::where(function ($q) use ($userId) {
                                $q->where('from_user_id', $userId)
                                    ->orWhereJsonContains('to_user_ids', (string) $userId);
                            })->where('folder', 'trash')->count(),

            'starred' => MailBox::where(function ($q) use ($userId) {
                                $q->where('from_user_id', $userId)
                                    ->orWhereJsonContains('to_user_ids', (string) $userId);
                            })->where('is_starred', 1)->count(),
        ];

        /** --------------------------------------------------------------
         * Load User's POP3 Email Configuration
         * -------------------------------------------------------------- */
        $config = EmailConfiguration::where('user_id', $userId)->first();

        /** --------------------------------------------------------------
         * Fetch External Emails (POP3) & Store into DB
         * -------------------------------------------------------------- */
        if ($config) {
            try {
                $pop3mails = CustomHelper::fetchPOP3(
                    host: $config->incoming_host,
                    port: $config->incoming_port,
                    username: $config->incoming_username,
                    password: $config->incoming_password,
                    ssl: $config->incoming_encryption === 'ssl'
                );
            
                // Save fetched mails into database (no duplicates)
                $this->storeFetchedEmails($pop3mails);

            } catch (\Exception $e) {
                \Log::error("POP3 Fetch Failed: " . $e->getMessage());
            }
        }

        /** --------------------------------------------------------------
         * Load Stored Mails from DB (Inbox Only)
         * -------------------------------------------------------------- */
        $storedMails = MailBox::with(['fromUser', 'userData'])
        ->where('folder', 'inbox')
        ->where('owner_id', $userId)
        ->orderBy('external_date', 'desc')
        ->orderBy('created_at', 'desc')
        ->get();

        /** --------------------------------------------------------------
         * Load View
         * -------------------------------------------------------------- */
        $data = [
            'meta_title' => 'Email',
            'employees'  => Employee::with('user')->get(),
            'counts'     => $counts,
            'imapMails'  => $storedMails,
        ];

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

        $toUserIds = json_decode($request->to_user_ids, true); // ensure this is an array

        if (is_array($toUserIds) && !empty($toUserIds)) {
            $emails = User::whereIn('id', $toUserIds)->pluck('email')->toArray();
        } else {
            $emails = [];
        }

        // Send notification email
        $htmlBody = view('emails.notification', [
            'name' => 'Team',
            'message' => 'You are receiving a new email',
        ])->render();

        CustomHelper::sendNotificationMail(
            $emails,
            $mail->subject,
            $htmlBody,
        );

        return response()->json(['status' => true, 'message' => 'Mail saved successfully!']);
    }


    /**
     * Display the specified resource.
     */
    public function show(MailBox $mailBox)
    {
        // Load relations
        $mailBox->load(['fromUser', 'userData']);

        if (!$mailBox) {
            return response()->json([
                'status' => false,
                'message' => 'Mail not found.'
            ], 404);
        }

        // Extract + clean message before sending to view
        $mailBox->message = $this->formatEmailBody($mailBox);

        $html = view('mailBox.readEmail', ['mail' => $mailBox])->render();

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
    public function destroy(Request $request){

            $mailIds = $request->input('mailIds');

            if (!is_array($mailIds) || empty($mailIds)) {
                return response()->json(['status' => false, 'message' => 'No emails selected.'], 400);
            }

            // Delete emails permanently
            MailBox::whereIn('id', $mailIds)->delete();
            return response()->json(['status' => true, 'message' => 'Emails deleted successfully.']);
        }

    public function folder($folder)
{
    $allowedFolders = ['inbox', 'draft', 'sent', 'starred', 'spam', 'trash'];

    if (!in_array($folder, $allowedFolders)) {
        return response()->json([
            'status' => false,
            'message' => 'Invalid folder specified.'
        ], 400);
    }

    $userId = auth()->id();

    $query = MailBox::with(['fromUser', 'userData']);

    switch ($folder) {
        case 'inbox':
            // External POP3 + Internal mailbox emails
            $query->where('folder', 'inbox')
                  ->where('owner_id', $userId);
            break;

        case 'sent':
            // Internal emails only
            $query->where('from_user_id', $userId)
                  ->where('folder', 'sent');
            break;

        case 'draft':
            $query->where('from_user_id', $userId)
                  ->where('folder', 'draft');
            break;

        case 'starred':
            // Starred internal + starred external
            $query->where('is_starred', 1)
                  ->where('owner_id', $userId);
            break;
        case 'spam':
        case 'trash':
            // External + Internal
            $query->where('folder', $folder)
                  ->where('owner_id', $userId);
            break;
    }

    $mails = $query->orderByRaw('COALESCE(external_date, created_at) DESC')->get();

    return response()->json([
        'status' => true,
        'folder' => $folder,
        'data'   => $mails
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

    public function markAsRead(Request $request){
        $validated = $request->validate([
            'mailIds' => 'required|array'
        ]);
        MailBox::whereIn('id', $validated['mailIds'])->update([
            'mark_as_read' => 1
        ]);

        return response()->json(['status' => 'success']);
    }

    public function markRead(Request $request){
        $request->validate([
            'mailId' => 'required|integer|exists:mail_boxes,id',
        ]);

        $mail = MailBox::find($request->mailId);

        // Toggle is_starred value
        $mail->mark_as_read = $mail->mark_as_read ? 0 : 1;
        $mail->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Mail mark as read updated.',
        ]);
    }


    private function storeFetchedEmails($emails){
    $userId = auth()->id();

    foreach ($emails as $email) {

        $messageId = $email['message_id']
            ?: md5(
                ($email['subject'] ?? '') .
                ($email['date'] ?? '') .
                ($email['from'] ?? '') .
                ($email['body_plain'] ?? '')
            );

        if (MailBox::where('external_email_id', $messageId)->exists()) {
            continue;
        }

        $body = $email['body_html']
            ?: $email['body_plain']
            ?: '(No Content)';

        try {
            MailBox::create([
                'owner_id'          => $userId,
                'from_user_id'      => $userId,
                'to_user_ids'       => json_encode([]),
                'cc_user_ids'       => json_encode([]),
                'bcc_user_ids'      => json_encode([]),

                'subject'           => $email['subject'] ?? '(No Subject)',
                'message'           => $body,

                'folder'            => 'inbox',
                'status'            => 0,
                'is_starred'        => 0,
                'mark_as_read'      => 0,

                'attachments'       => json_encode($email['attachments'] ?? []),
                'raw_headers'       => json_encode($email['headers'] ?? null),

                'external_email_id' => $messageId,
                'external_from'     => $email['from'] ?? '',
                'external_date'     => $email['date'] ?? null,
            ]);

        } catch (\Exception $e) {
            dd("Insert failed: " . $e->getMessage());
        }
    }
}

private function formatEmailBody($mail)
{
    $body = $mail->message;
    $headers = json_decode($mail->raw_headers ?? "[]", true);

    // 1. If Outlook HTML exists inside X-ALT-DESC
    if (isset($headers['X-ALT-DESC'])) {
        $raw = $headers['X-ALT-DESC'];

        // Remove prefix
        $raw = preg_replace('/X-ALT-DESC;FMTTYPE=text\/html:/i', '', $raw);

        // Use this as body
        return $this->cleanHtml($raw);
    }

    // 2. If body contains VCALENDAR → extract only description
    if (str_contains($body, 'BEGIN:VCALENDAR')) {

        // Extract DESCRIPTION block
        if (preg_match('/DESCRIPTION:(.*?)DTEND/s', $body, $matches)) {
            $desc = $matches[1];

            // Clean Outlook escaped chars: \n \, \;
            $desc = str_replace(['\\n', '\\,', '\\;'], ["\n", ',', ';'], $desc);

            return nl2br(e(trim($desc)));
        }

        // If no description found, fallback
        return '(This is a calendar event email)';
    }

    // 3. If HTML body — return safely
    if (stripos($body, '<html') !== false || stripos($body, '<p') !== false) {
        return $this->cleanHtml($body);
    }

    // 4. Plain text → convert \n to <br>
    return nl2br(e($body));
}

}
