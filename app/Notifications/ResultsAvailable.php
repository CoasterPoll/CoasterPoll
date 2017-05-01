<?php

namespace ChaseH\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ResultsAvailable extends Notification
{
    use Queueable;

    protected $group;
    protected $filename;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($group, $filename)
    {
        $this->group = $group;
        $this->filename = $filename;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line("We've been working hard, and finally finished.")
                    ->action("View {$this->group}", url(route('coasters.results.manage')))
                    ->line($notifiable->name.', your results are now available!');
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
            'title' => "Processing Complete!",
            'link' => route('coasters.results.manage'),
            'body' => "{$this->group} results available!"
        ];
    }
}
