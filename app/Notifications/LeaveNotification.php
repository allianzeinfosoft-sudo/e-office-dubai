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
    protected $user_details;
    protected $type;
    protected $recipient_info;

    public function __construct($leave, $user_details, $type, $recipient_info)
    {
       $this->leave = $leave;
       $this->user_details = $user_details;
       $this->type = $type;
       $this->$recipient_info = $recipient_info;
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

        switch ($this->type){
            case 'apply':
                return (new MailMessage)
                        ->subject('New Leave Application Received')
                        ->greeting('Hello ' . $this->recipient_info->employee->full_name . ',')
                        ->line("A new leave request has been submitted by **{$this->leave->employee_name}**.")
                        ->line("Leave Duration: **{$this->leave->leave_from}** to **{$this->leave->leave_to}**")
                        ->line("Reason: {$this->leave->reason}") // Optional: include if available
                        ->action('Review Leave Request', url('/admin/leaves/' . $this->leave->id))
                        ->line('Please review and take necessary action on this request.')
                        ->salutation('Regards,')
                        ->line('Allianze Infosoft');
            case 'approved':
                    return (new MailMessage)
                        ->subject('Your Leave Request Has Been Approved')
                        ->greeting('Hello ' . $this->user_details->full_name . ',')
                        ->line("Good news! Your leave request from **{$this->leave->leave_from}** to **{$this->leave->leave_to}** has been **approved**.")
                        ->action('View Leave Status', url('/leaves'))
                        ->line('We hope you have a restful and productive time off. If you have any questions, feel free to contact HR.')
                        ->salutation('Regards,')
                        ->line('Allianze Infosoft');
            case 'rejected':
                    return (new MailMessage)
                        ->subject('Your Leave Request Has Been Rejected')
                        ->greeting('Hello ' . $this->user_details->full_name . ',')
                        ->line("We regret to inform you that your leave request from **{$this->leave->leave_from}** to **{$this->leave->leave_to}** has been **rejected**.")
                        ->line(!empty($this->leave->rejection_reason)
                            ? "Reason: {$this->leave->rejection_reason}"
                            : 'Please contact your manager or HR for more details.')
                        ->action('View Leave Status', url('/leaves'))
                        ->line('If you have any questions or concerns, feel free to reach out to us.')
                        ->salutation('Regards,')
                        ->line('Allianze Infosoft');

        }


    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {


        $receiverEmail = $notifiable->email;

        switch ($this->type){
            case 'apply':
                return [
                    'leave_id' => $this->leave->id,
                     'user_id' => $this->leave->user_id,
                     'status' => $this->leave->status,
                     'message' => "Your leave request from {$this->leave->start_date} to {$this->leave->end_date} has been {$this->leave->status}.",
                 ];
            case 'approved':
                return [
                    'leave_id' => $this->leave->id,
                     'user_id' => $this->leave->user_id,
                     'status' => $this->leave->status,
                     'message' => "Your leave request from {$this->leave->start_date} to {$this->leave->end_date} has been {$this->leave->status}.",
                 ];
            case 'rejected':
                return [
                    'leave_id' => $this->leave->id,
                     'user_id' => $this->leave->user_id,
                     'status' => $this->leave->status,
                     'message' => "Your leave request from {$this->leave->start_date} to {$this->leave->end_date} has been {$this->leave->status}.",
                 ];

        }
    }
}
