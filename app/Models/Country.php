<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Country extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name', 'short_name', 'phonecode'
    ];

    public function users(): HasMany{
        return $this->hasMany(User::class);
    }
    public function comapnies(): HasMany{
        return $this->hasMany(Company::class);
    }
}
