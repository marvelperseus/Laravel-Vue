<?php

namespace App\Notifications;

use App\Mail\WebAppointmentMailable;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class WebAppointmentNotify extends Notification
{
    use Queueable;

    protected $subject;
    protected $body;

    /**
     * Create a new notification instance.
     *
     * @param string $subject
     * @param string $body
     * @return void
     */
    public function __construct(string $subject, string $body)
    {
        $this->subject = $subject;
        $this->body = $body;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new WebAppointmentMailable())
            ->to($notifiable->email)
            ->subject($this->subject)
            ->with(['body' => $this->body]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
