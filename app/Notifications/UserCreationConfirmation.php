<?php

namespace App\Notifications;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class UserCreationConfirmation extends Notification
{
    use Queueable;
    
    /**
     * @var
     */
    private $password;
    
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($password)
    {
        $this->password = $password;
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
        $mail = (new MailMessage)
                    ->subject('Регистрация на Big Master')
                    ->greeting("Здравствуйте {$notifiable->name}!")
                    ->line('Вы зарегестрированы на Big Master')
                    ->line("Ваш email: {$notifiable->email}")
                    ->line("Ваш пароль: {$this->password}");
        if ($notifiable->role == User::ROLE_MANAGER) {
            $mail->action('Перейти', url('/admin'));
        }
        
        return $mail;
    }
}
