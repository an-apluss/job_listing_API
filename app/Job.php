<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Job extends Model
{

    protected $fillable = [
        'title', 'company', 'description', 'user_id', 'location', 'industry', 'job_type', 'salary'
    ];

    protected $hidden = ['updated_at', 'created_at'];
}
