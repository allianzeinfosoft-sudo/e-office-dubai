<?php

namespace App\Mail;

use App\Models\TrainingTest;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TrainingTestInvitationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $trainingTest;
    public $user;

      public function __construct(TrainingTest $trainingTest, User $user)
    {
        $this->trainingTest = $trainingTest;
        $this->user = $user;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Training Test Invitation Mail',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.training_test_invitation',
            with: [
                'trainingTest' => $this->trainingTest,
                'user'         => $this->user,
                'testUrl'      => route('training-tests.index'),
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
