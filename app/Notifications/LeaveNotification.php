<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;

class LeaveNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    protected $leave;
    protected $employee;
    public function __construct($leave,$user_details)
    {
       $this->leave = $leave;
       $this->employee = $user_details;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail','database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $receiverEmail = $notifiable->email;
        return (new MailMessage)
            ->subject('Leave Request Update')
            ->greeting('Hello ' . $this->employee->full_name . ',')
            ->line("We have received your leave request from **{$this->leave->leave_from}** to **{$this->leave->leave_to}**.")
            ->action('View Request', url('/leaves'))
            ->line('Thank you for using our application. If you have any questions, feel free to reach out to us.')
            ->salutation('Regards,')
            ->line('Allianze Infosoft');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
           'leave_id' => $this->leave->id,
            'user_id' => $this->leave->user_id,
            'status' => $this->leave->status,
            'message' => "Your leave request from {$this->leave->start_date} to {$this->leave->end_date} has been {$this->leave->status}.",
        ];
    }
}
