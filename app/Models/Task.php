<?php

namespace App\Models;

use App\Models\Project;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = ['title', 'description', 'completed', 'project_id'];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}