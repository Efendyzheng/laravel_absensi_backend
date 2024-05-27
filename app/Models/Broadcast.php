<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Broadcast extends Model
{
    use HasFactory;

    protected $appends = ['department_names'];

    protected $fillable = [
        'description',
        'department',
    ];

    public function getDepartmentNamesAttribute()
    {
        if (!$this->department) {
            return [];
        }

        $departmentIds = explode(',', $this->department);

        return Department::whereIn('id', $departmentIds)
            ->pluck('name')
            ->toArray();
    }
}
