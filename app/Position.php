<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    protected $table = 'position';
    public $timestamps = false;
    public $primaryKey  = 'idpos';

    protected $casts = [
        'permissions' => 'array',
    ];
    
    public function users()
    {
        return $this->belongsToMany(User::class, 'permission');
    }

    public function hasAccess(array $permissions): bool
    {
        foreach ($permissions as $permission) {
            if ($this->hasPermission($permission))
                return true;
        }
        return false;
    }

    private function hasPermission(string $permission): bool
    {
        return $this->permissions[$permission] ?? false;
    }
}
