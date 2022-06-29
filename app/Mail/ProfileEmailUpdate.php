<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;

class ProfileEmailUpdate extends Mailable
{
    use Queueable, SerializesModels;

    protected $userId;

    protected $newEmailAddress;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($userId, $newEmailAddress)
    {
        $this->userId = $userId;
        $this->newEmailAddress = $newEmailAddress;
    }

    /**
     * Build the message.
     *
     * @return ProfileEmailUpdate
     */
    public function build(): ProfileEmailUpdate
    {
        return $this
            ->subject('Please Activate Your New Email Address')
            ->markdown('mail.profile.email.updated', ['url' => $this->verificationUrl()]);
    }

    /**
     * Get the verification URL for the given notifiable.
     *
     * @return string
     */
    protected function verificationUrl(): string
    {
        return URL::temporarySignedRoute(
            'verification.profile.email.verify',
            Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),
            [
                'user' => $this->userId,
                'hash' => sha1($this->newEmailAddress),
            ]
        );
    }

}
