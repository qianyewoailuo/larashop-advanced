<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;

class EmailVerificationNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
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
        // 生成token
        $token = Str::random(16);
        // 写入缓存
        Cache::set('email_verification_'.$notifiable->email,$token,30);
        // 邮箱验证链接-@verify
        $url = route('email_verification.verify', ['email' => $notifiable->email, 'token' => $token]);

        return (new MailMessage)
                    ->greeting($notifiable->name.'您好: ')
                    ->subject('注册成功,请验证您的邮箱')
                    ->line('请点击下方链接验证您的邮箱')
                    ->action('邮箱验证',$url)
                    ->line('如非本人操作,请忽略该邮件')
                    ->line('感谢您的注册');
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
