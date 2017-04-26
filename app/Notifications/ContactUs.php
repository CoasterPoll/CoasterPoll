<?php

namespace ChaseH\Notifications;

use ChaseH\Models\Contact;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class ContactUs extends Notification
{
    private $contact;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Contact $contact)
    {
        $this->contact = $contact;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['slack'];
    }

    public function toSlack($notifiable) {
        $contact = $this->contact;
        $url = url(route('admin.contact', ['id' => $contact->id]));

        $fields = [
            'From' => $contact->name,
            'Email' => $contact->email,
        ];

        if($contact->contactable !== null) {
            if($contact->contactable_type == "ChaseH\\Models\\Coasters\\Coaster") {
                $fields['Type'] = "Coaster";
            }
        }

        $message =  (new SlackMessage())
            ->from('CoasterPoll', ':ghost:')
            ->to('#general')
            ->content(str_limit($contact->message))
            ->attachment(function($attachment) use ($contact, $url, $fields) {
                $attachment->title("New Message from {$contact->name}", $url)->fields($fields);
            });

        return $message;
    }
}
