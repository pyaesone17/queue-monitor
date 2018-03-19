<?php 

namespace Pyaesone17\QueueMonitor\App\Models;

use Illuminate\Database\Eloquent\Model;

class FailedJob extends Model
{
    protected $casts = [
        'payload' => 'object',
        'failed_at' => 'datetime'
    ];
}
