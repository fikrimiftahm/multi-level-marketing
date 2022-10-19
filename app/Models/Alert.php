<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Alert extends Model
{
    const STATUS_OK = 200;
    const STATUS_ERROR = 500;
    const TYPE_ERROR = "error";
    const TYPE_INFO = "info";
    const TYPE_WARNING = "warning";
    const TYPE_SUCCESS = "SUCCESS";
    const MESSAGE_FAILED_GET_DATA = "Failed to Get Data";
    const MESSAGE_SUCCESS_GET_DATA = "Success to Get Data";
    const MESSAGE_DATA_NOT_FOUND = "Data Not Found";
    const MESSAGE_PARTNER_DOESNT_HAVE_DEV_APP = "Partner doesn't have Developer Apps";

    protected static $types = [
        'error' => self::TYPE_ERROR,
        'info' => self::TYPE_INFO,
        'warning' => self::TYPE_INFO,
        'success' => self::TYPE_SUCCESS
    ];

    protected static $statuses = [
        'OK' => self::STATUS_OK,
        'SERVER_ERROR' => self::STATUS_ERROR
    ];

    protected static $message = [
        'FAILED_GET_DATA' => self::MESSAGE_FAILED_GET_DATA,
        'SUCCESS_GET_DATA' => self::MESSAGE_SUCCESS_GET_DATA,
        'DATA_NOT_FOUND' => self::MESSAGE_DATA_NOT_FOUND
    ];

    public function type($type)
    {
        return $this->types[$type] ? $this->types[$type] : null;
    }

    public function status($status)
    {
        return $this->statuses[$status] ? $this->statuses[$status] : null;
    }
}
