<?php
/**
 * @author 河南童伴童学网络科技
 * @link http://www.itbtx.cn
 * User: lzf
 * Date: 2022/6/15
 * Time: 2:05 PM
 */
namespace App\Helper;

use Hyperf\Utils\Arr;

/**
 * 模型属性验证器.
 */
class ModelValidator
{
    /**
     * 决定字段是必填还是选填.
     * @var array 基本的条件,可以是 required:必填,sometimes:选填
     */
    private $if_rules;

    /**
     * @inheritDoc
     */
    final public function __construct($if_rules) {
        $this->if_rules = $if_rules;
    }

    private function getIfRules() {
        $rtn = [];
        foreach ($this->if_rules as $field => $rule) {
            if (is_string($rule)) {
                if (strpos($rule, '|') !== false) {
                    $rule_arr = explode('|', $rule);
                } else {
                    $rule_arr = [$rule];
                }
            } else if (is_array($rule)) {
                $rule_arr = $rule;
            }
            $rtn[$field] = $rule_arr;
        }
        return $rtn;
    }

    /**
     * 组合模型字段的验证规则. $if_rules + $must_rules
     * @return array Validator的验证规则的rules数组
     */
    final public function getValidRules() {
        $must_rules = $this->getAttrsMustRules();
        $if_rules = $this->getIfRules();
        if (empty($if_rules)) {
            return [];
        }
        $merge_rules = [];
        foreach ($if_rules as $field => $if_rule) {
            $merge_rules[$field] = array_merge(
                Arr::wrap($if_rule),
                Arr::wrap($must_rules[$field])
            );
        }
        return $merge_rules;
    }

    /**
     * 强制的验证规则, 数据存储必须要满足的.
     * @var array
     */
    private $must_rules;

    /**
     * @inheritDoc
     */
    public function getAttrsMustRules() {
        return $this->must_rules;
    }
    /**
     * 从model获取出来mustRules,写到这里.
     * @param $must_rules
     * @return void
     */
    public function setAttrsMustRulesFromModel($must_rules) {
        $this->must_rules = $must_rules;
    }
}