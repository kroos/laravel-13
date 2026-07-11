<?php
namespace App\Models\System;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// use App\Models\Model;

class JobBatch extends Model
{
	use HasFactory;

	protected $table = 'job_batches';
	protected $guarded = [];
}
