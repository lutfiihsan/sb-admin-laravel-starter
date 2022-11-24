<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModelHasPermission extends Model
{
    use HasFactory;

    protected $table = 'model_has_permissions';

    protected $fillable = [
        'permission_id',
        'model_id',
        'model_type',
    ];

    public function permission()
    {
        return $this->belongsTo(Permission::class);
    }
}
