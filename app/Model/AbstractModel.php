<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
namespace App\Model;

use App\Helper\ModelValidator;
use App\Traits\ValidatorTrait;
use Hyperf\DbConnection\Model\Model as BaseModel;

abstract class AbstractModel extends BaseModel
{
    use ValidatorTrait;
    /**
     * 验证模型属性.
     */
    public static function validAttrs($data, $if_rules) {
        // $validator = new ModelValidator($if_rules);
        // $validator->setAttrsMustRulesFromModel(static::getMustValidRules());
        // $merge_rules = $validator->getValidRules();
        // $model_name = class_basename(static::class);
        // return static::validData("模型 $model_name 的属性验证", $data, $merge_rules, [], []);
    }
}
