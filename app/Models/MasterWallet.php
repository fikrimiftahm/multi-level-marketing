<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterWallet extends Model
{
    use HasFactory;

    protected $connection = 'mysql_2';
    protected $table = 'master_wallet';
    public $timestamps = false;

    protected $fillable = [
        'wallet_id', 
        'wallet_name', 
        'wallet_prefix', 
        'initial_balance', 
        'balance', 
        'total_balance', 
        'unit', 
        'created_date', 
        'expired_date', 
        'developer_app_id', 
        'developer_app_name', 
        'status', 
        'sms_notification', 
        'created_at', 
        'updated_at'
    ];

    public function getInitialBalanceAttribute($value)
    {
        return number_format($value);
    }

    public function getBalanceAttribute($value)
    {
        return number_format($value);
    }

    public function getStatusAttribute($value)
    {
        return $value == '1' ? 'Active' : 'Inactive';
    }

    public function getExpiredDateAttribute($value)
    {
        return date('d M Y', strtotime($value));
    }

    public function developerApp()
    {
        return $this->belongsTo(MasterDeveloperApp::class, 'developer_app_id', 'developer_app_id');
    }

    public function unit()
    {
        return $this->belongsTo(MasterUnit::class, 'unit', 'unit_id');
    }
}
