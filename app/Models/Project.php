<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function deployments(): HasMany
    {
        return $this->hasMany(Deployment::class);
    }

    public function servers(): HasMany
    {
        return $this->hasMany(Server::class);
    }
}
