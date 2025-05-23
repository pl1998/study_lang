<?php

namespace App\Models;

use Dcat\Admin\Traits\HasDateTimeFormatter;
use Illuminate\Database\Eloquent\Model;
use App\Enum\CacheEnum;
class ConfigPay extends BaseModel
{
	use HasDateTimeFormatter;
    protected $table = 'config_pay';

    protected $guarded = [];

    public static function getConfig()
    {
        return cache()->remember(CacheEnum::CONFIG_PAY, 3600, function () {
            return self::first()?->toArray();
        });
    }
}
