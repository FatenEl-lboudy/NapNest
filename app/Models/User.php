<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\SleepMetric;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $primaryKey = 'patient_id';
    protected $fillable = [
        'patient_name',
        'email',
        'password',
        'gender',
        'birth_date',
        'otp_code',
        'otp_expires_at',

    ];
    public function psqiTests()
    {
        return $this->hasMany(PSQITest::class, 'patient_id');
    }


    public function myPath()
    {
        return $this->hasOne(MyPath::class, 'user_id');
    }

    public function nestNotes(): HasMany
    {
        return $this->hasMany(NestNotes::class);
    }

    public function alarm()
    {
        return $this->hasOne(Alarm::class);
    }

    public function device()
    {
        return $this->hasOne(Device::class);
    }

    public function sleepMetrics(): HasMany
    {
        return $this->hasMany(SleepMetric::class, 'patient_id', 'patient_id');
    }

    public function sleepTunes(): HasMany
    {
        return $this->hasMany(SleepTune::class);
    }

    public function cbtTechniques(): HasMany
    {
        return $this->hasMany(CbtTechnique::class);
    }



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
    ];
}
