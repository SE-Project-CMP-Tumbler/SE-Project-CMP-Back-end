<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Block extends Model
{
    use HasFactory;

    /**
     * The table this model refers to.
     *
     * @var string
     */
    protected $table = "blocks";

    /**
     * The primary keys of the blocks table.
     *
     * @var string[]
     */
    protected $primaryKey = ['blocker_id', 'blocked_id'];

    /**
     * The incrementing state of the primary keys.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'blocker_id',
        'blocked_id'
    ];
}
