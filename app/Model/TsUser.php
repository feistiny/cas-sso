<?php

declare (strict_types=1);
namespace App\Model;

/**
 * @property int $uid
 * @property string $username
 * @property string $password
 */
class TsUser extends AbstractModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'ts_user';
    protected $primaryKey = 'uid';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['username', 'password',];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['uid' => 'integer'];

    public function tgt() {
        $this->hasOne(TsTgt::class, 'uid', 'uid');
    }
}