<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Company extends Authenticatable
{

    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get rel logo.
     *
     * @return HasOne
     */
    public function logo(): HasOne
    {
        return $this->hasOne('App\Models\CompanyLogo');
    }

    /**
     * Get rel images.
     *
     * @return HasMany
     */
    public function images(): HasMany
    {
        return $this->hasMany('App\Models\CompanyImage');
    }
}
