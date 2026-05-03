<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'user_type',
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
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Get the bookings for the user.
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Get the transactions for the user.
     */
    public function transactions()
    {
        return $this->hasMany(PhonePeTransaction::class);
    }

    /**
     * Resume related data
     */
    public function resumePersonal() { return $this->hasMany(ResumeNamePersonal::class); }
    public function resumeExperience() { return $this->hasMany(ResumeNameExperience::class); }
    public function resumeEducation() { return $this->hasMany(ResumeNameEducation::class); }
    public function resumeSkill() { return $this->hasMany(ResumeNameSkill::class); }
    public function resumeCertification() { return $this->hasMany(ResumeNameCertification::class); }
    public function resumeProject() { return $this->hasMany(ResumeNameProject::class); }

    /**
     * Cover letter related data
     */
    public function coverPersonal() { return $this->hasMany(CoverPersonal::class); }
    public function coverRecipient() { return $this->hasMany(CoverRecipientDetail::class); }
    public function coverBody() { return $this->hasMany(CoverLetterBody::class); }
}
