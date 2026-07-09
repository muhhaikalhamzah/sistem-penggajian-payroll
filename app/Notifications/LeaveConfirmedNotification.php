<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\LeaveRequest;

class LeaveConfirmedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $leaveRequest;

    /**
     * Create a new notification instance.
     */
    public function __construct(LeaveRequest $leaveRequest)
    {
        $this->leaveRequest = $leaveRequest;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'leave_id' => $this->leaveRequest->id,
            'status' => $this->leaveRequest->status,
            'message' => 'Pengajuan cuti Anda telah ' . strtolower($this->leaveRequest->status) . '.',
            'url' => route('my-leaves.show', $this->leaveRequest->id),
        ];
    }
}
