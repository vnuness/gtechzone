<?php

namespace App\Notifications\Credentials;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class NewUser extends Notification
{
    use Queueable;

    private $user_to;

    private $user_from;

    private $pass;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $user_to, $pass, User $user_from)
    {
        $this->user_to = $user_to;
        $this->user_from = $user_from;
        $this->pass = $pass;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {

        return (new MailMessage)
            ->from($this->user_from->email, $this->user_from->name)
            ->subject('Bem Vindo ao ' . config('app.name', 'MeetaWeb'))
            ->markdown('credentials.users.email.welcome', ['user' => $this->user_to, 'pass' => $this->pass]);

    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
