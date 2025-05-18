<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

/**
 * User Model
 */
class User extends Model
{
    /**
     * The table associated with the model
     */
    protected string $table = 'users';
    
    /**
     * The attributes that are mass assignable
     */
    protected array $fillable = [
        'name',
        'email',
        'password'
    ];
    
    /**
     * Get user's display name
     */
    public function getDisplayName(): string
    {
        return $this->name ?? 'Unknown User';
    }
    
    /**
     * Check if the given password matches the user's password
     */
    public function verifyPassword(string $password): bool
    {
        return password_verify($password, $this->password);
    }
}