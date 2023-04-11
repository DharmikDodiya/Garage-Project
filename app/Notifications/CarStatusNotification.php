<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CarStatusNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public $car_servicing_job;
    public $owner;
    public $car;
    public function __construct($owner,$car_servicing_job,$car)
    {
        $this->owner = $owner;
        $this->car = $car;
        $this->car_servicing_job = $car_servicing_job;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->greeting('Hello ,'.$this->owner->first_name ." ".$this->owner->last_name)
                    ->line('Your Car Status Is Updated')
                    ->line('Status :'.$this->car_servicing_job->status)
                    ->line('Car Details')
                    ->line('Compny Name :'.$this->car->company_name)
                    ->line('Model Name :'.$this->car->model_name)
                    ->line('Thank you for Visiting our Garage!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
