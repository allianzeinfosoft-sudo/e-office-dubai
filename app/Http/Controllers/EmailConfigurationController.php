<?php

namespace App\Http\Controllers;

use App\Models\EmailConfiguration;
use Illuminate\Http\Request;

class EmailConfigurationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $data['config'] = EmailConfiguration::where('user_id', auth()->id())->first();
        return view('email-configurations.index', $data);
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
    public function store(Request $request)
    {
        $validated = $request->validate([
            'mail_protocol' => 'required|in:imap,pop3',
            'incoming_host' => 'required|string',
            'incoming_port' => 'required|integer',
            'incoming_encryption' => 'required|string',
            'incoming_username' => 'required|string',
            'incoming_password' => 'required|string',
        ]);

        $validated['user_id'] = auth()->id();

        EmailConfiguration::create($validated);

        return redirect()->back()->with('success', 'Email receiving configuration saved.');
    }

    public function edit($id)
    {
        $config = EmailConfiguration::findOrFail($id);
        return view('email_configurations.form', compact('config'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'mail_protocol' => 'required|in:imap,pop3',
            'incoming_host' => 'required|string',
            'incoming_port' => 'required|integer',
            'incoming_encryption' => 'required|string',
            'incoming_username' => 'required|string',
            'incoming_password' => 'required|string',
        ]);

        $config = EmailConfiguration::findOrFail($id);
        $config->update($validated);

        return redirect()->back()->with('success', 'Configuration updated successfully.');
    }


    /**
     * Display the specified resource.
     */
    public function show(EmailConfiguration $emailConfiguration)
    {
        //
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EmailConfiguration $emailConfiguration)
    {
        //
    }
}
