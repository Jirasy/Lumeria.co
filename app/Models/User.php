<?php


namespace App\Models;


// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    public const ROLE_CUSTOMER = 'customer';
    public const ROLE_ADMIN    = 'admin';

    public const ROLES   = [self::ROLE_CUSTOMER, self::ROLE_ADMIN];
    public const GENDERS = ['male', 'female', 'other'];

    /** Disk dan batas ukuran foto profil (KB). */
    public const AVATAR_DISK   = 'local';
    public const AVATAR_MAX_KB = 1024;


    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'gender',
        'birth_date',
        'password',
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


    // Sengaja TIDAK masuk $fillable: peran hanya boleh diubah lewat seeder, tinker, atau
    // panel Kelola Pengguna (admin), supaya form registrasi dan profil tidak bisa dipakai
    // untuk menjadikan diri sendiri admin. avatar_path juga hanya diisi oleh ProfileController.
    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    /** Dua huruf awal nama untuk avatar cadangan. */
    public function initials(): string
    {
        $parts = preg_split('/\s+/', trim((string) $this->name), -1, PREG_SPLIT_NO_EMPTY) ?: [];
        $first = $parts[0] ?? '?';
        $last  = count($parts) > 1 ? $parts[count($parts) - 1] : '';

        return Str::upper(Str::substr($first, 0, 1) . Str::substr($last, 0, 1));
    }

    public function hasAvatar(): bool
    {
        return $this->avatar_path && Storage::disk(self::AVATAR_DISK)->exists($this->avatar_path);
    }

    /** URL foto profil (rute terautentikasi), atau null bila belum ada foto. */
    public function avatarUrl(): ?string
    {
        if (! $this->hasAvatar()) {
            return null;
        }

        return route('avatar.show', ['user' => $this->id, 'v' => $this->updated_at?->timestamp]);
    }

    public function deleteAvatarFile(): void
    {
        if ($this->avatar_path) {
            Storage::disk(self::AVATAR_DISK)->delete($this->avatar_path);
        }
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'birth_date' => 'date',
            'password' => 'hashed',
        ];
    }
}
