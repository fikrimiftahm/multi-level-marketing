<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterDeveloperApp extends Model
{
    use HasFactory;

    protected $connection = 'mysql_2';
    protected $table = 'master_developer_app';
    public $timestamps = false;

    protected $fillable = [
        'developer_app_id', 
        'developer_app_name', 
        'developer_app_alias', 
        'partner_id', 
        'service_id', 
        'payment_id', 
        'tariff', 
        'status', 
        'pending_payment',
        'env'
    ];

    public function partner()
    {
        return $this->belongsTo(MasterPartner::class, 'partner_id', 'partner_id');
    }

    public function wallet()
    {
        return $this->hasOne(MasterWallet::class, 'developer_app_id', 'developer_app_id');
    }

    public function payment()
    {
        return $this->belongsTo(MasterPayment::class, 'payment_id', 'payment_id');
    }

    public function pricing()
    {
        return $this->hasMany(MasterPricing::class, ['developer_app_name', 'group'], ['developer_app_name', 'package_code']);
    }

    public function service()
    {
        return $this->belongsTo(MasterService::class, 'service_id', 'service_id');
    }
}
