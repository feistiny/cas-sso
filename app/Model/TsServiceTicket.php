<?php

declare (strict_types=1);
namespace App\Model;

use Hyperf\DbConnection\Model\Model;

/**
 * @property int $st_id
 * @property int $uid
 * @property int $tgt_id
 * @property int $service_id
 * @property int $used
 * @property string $expires_in
 * @property int $validate
 */
class TsServiceTicket extends Model
{
    const VALIDATE_YES = 1;
    const VALIDATE_NO = -1;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'ts_service_ticket';
    protected $primaryKey = 'st_id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['uid', 'tgt_id','service_id','expires_in','used'];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['st_id' => 'integer', 'uid' => 'integer', 'tgt_id' => 'integer', 'service_id' => 'integer', 'used' => 'integer', 'validate' => 'integer'];

    public function service() {
        return $this->hasOne(TsService::class, 'service_id', 'service_id'); 
    }
}