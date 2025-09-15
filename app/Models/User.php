<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasName;
use Filament\Panel;
class User extends Authenticatable implements FilamentUser, HasName
{
    use HasFactory, Notifiable,HasApiTokens;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'fname',
        'lname',
        'username',
        'email',
        'phone',
        'profile_picture',
        'password',
        'role',
        'verification_code',
        'is_verified',
        'email_verified_at', 
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password',
        'remember_token',
         'verification_code',

    ];

    /**
     * The attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'is_verified' => 'boolean',
            'password' => 'hashed',
        ];
    }


public function canAccessPanel(Panel $panel): bool
    {
    
        return $this->role === 'admin';
    }
public function getFilamentName(): string
{
    if ($this->fname && $this->lname) {
        return $this->fname . ' ' . $this->lname;
    }

    if ($this->username) {
        return $this->username;
    }

    if ($this->email) {
        return $this->email;
    }

    return 'User #' . $this->id; 
}
public function getFullNameAttribute(): string
{
    return "{$this->fname} {$this->lname}";
}



    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    */
    public function cart()
    {
        return $this->hasOne(Cart::class);
    }

    public function wishlist()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function locations()
    {
        return $this->hasMany(UserLocation::class);
    }

    public function coupons()
    {
        return $this->belongsToMany(Coupon::class, 'coupon_user');
    }

    public function articles(){
        return $this->hasMany(Article::class, 'author_id');
    }
}
