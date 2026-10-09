<?php

namespace App\Models;

use App\Models\Vital\UserVital;
use App\Models\Vital\Vital;
use App\Models\Workout\Workout;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'uuid',
        'name',
        'email',
        'password',
        'date_of_birth',
        'gender_id',
    ];

    protected static function booted()
    {
        static::creating(function ($user) {
            if (empty($user->uuid)) {
                $user->uuid = (string) \Illuminate\Support\Str::uuid();
            }
        });
    }

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'date_of_birth' => 'date',
    ];

    protected $table = 'user';

    public function workout(){
        return $this->hasMany(Workout::class, 'user_id');
    }

    public function gender(){
        return $this->belongsTo(Gender::class, 'gender_id');
    }

    public function vitals(){
        return $this->hasMany(UserVital::class, 'user_id');
    }

    public function userVitals(){
        return $this->hasMany(UserVital::class, 'user_id');
    }

    /**
     * Get the latest recorded vital for a given vital slug (e.g. 'weight', 'height').
     *
     * @param string $slug
     * @return \App\Models\Vital\UserVital|null
     */
    public function latestVital(string $slug)
    {
        return $this->vitals()
            ->whereHas('vital', function ($query) use ($slug) {
                $query->where('slug', $slug);
            })
            ->orderBy('date_measured', 'desc')
            ->orderBy('id', 'desc')
            ->first();
    }

    /**
     * Calculate current BMI from latest weight (kg) and height (cm).
     *
     * @return float|null
     */
    public function bmi()
    {
        $weightVital = $this->latestVital('weight');
        $heightVital = $this->latestVital('height');

        if ($weightVital && $heightVital && $weightVital->value > 0 && $heightVital->value > 0) {
            $heightM = $heightVital->value / 100;
            return round($weightVital->value / ($heightM * $heightM), 1);
        }

        return null;
    }

    /**
     * Calculate user age in full years from date_of_birth.
     *
     * @return int|null
     */
    public function age(): ?int
    {
        if (!$this->date_of_birth) {
            return null;
        }

        return \Carbon\Carbon::parse($this->date_of_birth)->age;
    }

    /**
     * Age-predicted maximum heart rate (Fox formula: 220 - age).
     *
     * @return int|null
     */
    public function maxHeartRate(): ?int
    {
        $age = $this->age();
        return $age ? max(100, 220 - $age) : null;
    }

    /**
     * Basal Metabolic Rate (BMR) in kcal/day using Mifflin-St Jeor equation.
     *
     * @return int|null
     */
    public function bmr(): ?int
    {
        $age = $this->age();
        $weightVital = $this->latestVital('weight');
        $heightVital = $this->latestVital('height');

        if ($age && $weightVital && $heightVital && $weightVital->value > 0 && $heightVital->value > 0) {
            $isFemale = ($this->gender && strtolower($this->gender->slug ?? $this->gender->name ?? '') === 'female');
            $offset = $isFemale ? -161 : 5;
            return (int) round((10 * $weightVital->value) + (6.25 * $heightVital->value) - (5 * $age) + $offset);
        }

        return null;
    }
}
