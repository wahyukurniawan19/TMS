<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @mixin IdeHelperTestCase
 */
class TestCase extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'automated',
        'priority',
        'suite_id',
        'data',
        'order',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $dates = ['deleted_at'];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function deleter()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }

    /* Data json format

     * {
          "preconditions": "45",

          "steps": [
            {
              "action": "step action",
              "result": "step result"
            },
            {
              "action": "step action",
              "result": "step result"
            }
          ]
        }
     */
}
