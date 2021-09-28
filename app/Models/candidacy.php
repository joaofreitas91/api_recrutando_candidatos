<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class candidacy extends Model
{
    use HasFactory;

    protected $fillable = [
        'candidate_id',
        'job_id',
    ];

    protected $hidden = [
        'updated_at',
        'created_at',
    ];

    //protected $table = 'candidacies';

    public function getCandidate()
    {
        return $this->hasOne(Candidate::class, 'id', 'candidate_id');
    }

    public function getJob()
    {
        return $this->hasOne(Job::class, 'id', 'job_id');
    }
}
