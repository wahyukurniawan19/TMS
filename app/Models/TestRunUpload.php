<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestRunUpload extends Model
{
    use HasFactory;

    protected $fillable = [
        'test_run_id',
        'test_case_id',
        'file_name',
        'file_path',
    ];
}
