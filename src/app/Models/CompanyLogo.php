<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyLogo extends Model
{
    public function company(): BelongsTo
    {
        return $this->belongsTo('App\Models\Company');
    }
}
