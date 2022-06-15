<?php

declare (strict_types=1);
namespace App\Model;

use Hyperf\DbConnection\Model\Model;
/**
 * @property int $service_id 
 * @property string $url 
 * @property string $logout_url 
 */
class TsService extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'ts_service';
    protected $primaryKey = 'service_id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['service_id' => 'integer'];
}