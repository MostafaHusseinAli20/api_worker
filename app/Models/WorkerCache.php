<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkerCache extends Model
{
    use HasFactory;
    protected $fillable = ['client_id', 'post_id', 'total'];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
