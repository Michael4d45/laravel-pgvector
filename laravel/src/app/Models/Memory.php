<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Pgvector\Laravel\Vector;

class Memory extends Model
{
    protected $connection = 'pgsql';

    protected $casts = ['embedding' => Vector::class];

    public function setEmbedding()
    {
        
    }
}
