<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Cashier\Billable;
use Illuminate\Database\Eloquent\Relations\HasOne;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, Billable;

    protected $table = 'users';


    public function sendPasswordResetNotification($token)
    {
        $this->notify(new class($token) extends \Illuminate\Auth\Notifications\ResetPassword {

            public function __construct($token)
            {
                parent::__construct($token);
            }

            public function toMail($notifiable)
            {
                return (new \Illuminate\Notifications\Messages\MailMessage)
                    ->subject('Reimpostazione Password')
                    ->view('emails.reset-password', [
                        'url' => $this->resetUrl($notifiable),
                        'user' => $notifiable
                    ]);
            }
        });
    }


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'surname',
        'activation_code',
        'registered_at',
        'last_login_at',
        'email',
        'email_verified_at',
        'password',
        'role',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'registered_at' => 'datetime',
        'last_login_at' => 'datetime',
        'email_verified_at' => 'datetime',
    ];

    public function student(): HasOne
    {
        return $this->hasOne(Student::class);
    }

    public function admin(): HasOne
    {
        return $this->hasOne(Admin::class);
    }
}
