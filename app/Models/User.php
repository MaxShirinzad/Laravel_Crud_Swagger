<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * @OA\Schema(
 *     schema="User",
 *     required={"id", "name", "email", "password"},
 *     @OA\Property(property="id", type="integer", example="3"),
 *     @OA\Property(property="name", type="string", example="user1"),
 *     @OA\Property(property="email", type="string", format="email", example="user1@example.com"),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2025-03-11 20:40:54"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-03-11 20:40:54"),
 *     @OA\Property(property="image", type="string", example="/users/images/user1.jpg")
 * )
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'image',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function imagePublicLink(): ?string
    {
        if ($this->image){
            return url('/') . '/users/images/' . $this->image ?? null;
        }
        return null;
    }


}
