<?php

declare (strict_types=1);
namespace App\Model;

use Hyperf\DbConnection\Model\Model;
/**
 * @property int $info_id 
 * @property string $username 
 */
class Tc2Info extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tc2_info';
    protected $primaryKey = 'info_id';
    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['info_id','username','session_id'];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['info_id' => 'integer'];
}