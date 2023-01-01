<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DeploymentStep extends Model
{
    use HasFactory;

    public function deployment(): BelongsTo
    {
        return $this->belongsTo(Deployment::class);
    }

}
