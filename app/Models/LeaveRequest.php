<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaveRequest extends Model
{
    protected $table = 'leave_requests';

    protected $guarded=['id'];

    public function employee() {
        return $this->belongsTo(User::class, 'employee_id');
    }

}
