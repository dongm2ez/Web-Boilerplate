<?php

namespace App\Models;

use App\Models\Attribute\UserProfileAttribute;
use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    use UserProfileAttribute;
    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [

    ];


    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'use_gravatar' => 'boolean'
    ];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
