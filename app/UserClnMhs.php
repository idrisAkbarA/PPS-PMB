<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class UserClnMhs extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'id'
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

    // Getters
    public function getUjian($periode_id = null)
    {
        $ujian = $this->ujian()
            ->with('jurusan')
            ->when($periode_id, function ($q) use ($periode_id) {
                return $q->where('periode_id', $periode_id);
            })->get();

        $this->attributes['ujian'] = $ujian;
    }

    // Relations
    public function ujian()
    {
        return $this->hasMany('App\Ujian');
    }
}
