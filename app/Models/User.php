<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Kolom yang boleh diisi secara massal (mass assignment).
     */
    protected $fillable = [
        "name",
        "email",
        "password",
        "role", // Tambahan: role user (admin / pegawai)
    ];

    /**
     * Kolom yang disembunyikan saat serialisasi (misal: ke JSON).
     */
    protected $hidden = ["password", "remember_token"];

    /**
     * Casting tipe data kolom secara otomatis.
     */
    protected function casts(): array
    {
        return [
            "email_verified_at" => "datetime",
            "password" => "hashed",
        ];
    }

    /**
     * Helper: cek apakah user ini adalah admin.
     * Dipakai di Blade: @if(auth()->user()->isAdmin())
     */
    public function isAdmin(): bool
    {
        return $this->role === "admin";
    }

    /**
     * Relasi One-to-One: satu User punya satu Employee (data HR).
     */
    public function employee()
    {
        return $this->hasOne(Employee::class);
    }
}
