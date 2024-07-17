<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use function App\Helpers\caseMatchTable;

class DeviceLogsProcessed extends BaseModel
{
    use HasFactory;

    protected $table = 'DeviceLogs_Processed';
    protected $primaryKey = 'DeviceLogId';
    public $timestamps = false;

    protected $fillable = [ 'DownloadDate', 'DeviceId', 'UserId', 'LogDate', 'Direction', 'C1', 'C2', 'C3', 'C4', 'C5', 'C6', 'C7', 'WorkCode' ];


    public function user()
    {
        return $this->belongsTo(User::class, 'UserId', 'emp_code');
    }
}
