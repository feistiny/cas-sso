<?php

declare (strict_types=1);
namespace App\Model;

use Hyperf\DbConnection\Model\Model;
/**
 * @property int $st_id 
 * @property int $validate 
 * @property string $session_id 
 */
class Tc2ServiceTicket extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tc2_service_ticket';
    public $timestamps = false;
    protected $primaryKey = 'st_id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['st_id', 'session_id', 'validate',];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['st_id' => 'integer', 'validate' => 'integer'];
}