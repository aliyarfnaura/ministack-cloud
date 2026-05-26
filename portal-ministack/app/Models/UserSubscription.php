<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSubscription extends Model
{
    public function plan()
    {
        return $this->belongsTo(SubscriptionPlan::class, 'plan_id');
    }
}
