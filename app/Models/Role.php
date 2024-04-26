<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    use HasFactory;
    use HasUuids;
    protected $primaryKey = 'id';
    protected $fillable = ['name','label','guard_name'];
    protected $hidden = ['guard_name'];
    protected $guard_name = 'sanctum';
    protected $visible = ['id', 'name','label'];
    public const roles = [
        'super-admin'=>'Super Admin',
        'admin' => 'Admin',
        'member' => 'Member',
        'accountant' => 'Accountant',
        'business-person' => 'Businessperson'
    ];
    public function delete()
    {
        return parent::delete();

        dd($this);
    }
}
