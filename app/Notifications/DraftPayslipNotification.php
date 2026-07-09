<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class DraftPayslipNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $period;

    /**
     * Create a new notification instance.
     */
    public function __construct($period)
    {
        $this->period = $period;
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
            'message' => 'Draft Slip Gaji untuk periode ' . $this->period . ' telah dibuat dan siap untuk di-review.',
            'url' => route('payslips.index'),
        ];
    }
}
