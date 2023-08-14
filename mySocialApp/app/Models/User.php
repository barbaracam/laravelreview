<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Post;
use App\Models\Follow;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'email',
        'password',
    ];

    protected function avatar(): Attribute {
        return Attribute::make(get: function($value){
            return $value ? '/storage/avatars/' . $value : '/fallback-avatar.jpg';
        });
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
        'password' => 'hashed',
    ];

    public function posts() {
        //user_id column name
        return $this->hasMany(Post::class, 'user_id');
    }

    public function followers() {

        return $this->hasMany(Follow::class, 'followeduser', 'id');
    }
    public function following(){
        
        return $this->hasMany(Follow::class, 'user_id', 'id');
    }

    public function feedPosts(){
        //hasmanythrough is for intermedia table relationships
        //last class, we want to end, 2 intermedium class, 3 foreign key itermedium table, 4 it is the foreing key for the last class to end, 5 local key actual model, 6 itermedium table the other foreign key for the extra relationship
        return $this->hasManyThrough(Post::class, Follow::class, 'user_id', 'user_id', 'id','followeduser' );
    }
}
