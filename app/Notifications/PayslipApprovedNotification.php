<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use App\Models\Payslip;

class PayslipApprovedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $payslip;

    /**
     * Create a new notification instance.
     */
    public function __construct(Payslip $payslip)
    {
        $this->payslip = $payslip;
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
            'payslip_id' => $this->payslip->id,
            'message' => 'Slip Gaji Anda untuk periode ' . $this->payslip->period . ' telah terbit.',
            'url' => route('payslips.show', $this->payslip->id),
        ];
    }
}
