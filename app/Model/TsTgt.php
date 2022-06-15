<?php

declare (strict_types=1);
namespace App\Model;

use Hyperf\DbConnection\Model\Model;
/**
 * @property int $tgt_id 
 * @property int $uid 
 * @property string $expires_in 
 * @property int $validate 
 */
class TsTgt extends Model
{
    const VALIDATE_YES = 1;
    const VALIDATE_NO = -1;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'ts_tgt';
    protected $primaryKey = 'tgt_id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['uid','expires_in','validate'];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['tgt_id' => 'integer', 'uid' => 'integer', 'validate' => 'integer'];

    public function user() {
        return $this->hasOne(TsUser::class, 'uid', 'uid');
    }
}