<?php

namespace App\Traits;

use App\Exception\BusinessException;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Validation\Contract\ValidatorFactoryInterface;

trait ValidatorTrait
{
    #[Inject]
    protected ValidatorFactoryInterface $validatorFactory;

    /**
     * 验证请求.
     * @param array $data
     * @param array $rules
     * @param array $msgs
     * @param array $attrs
     * @return array
     */
    public function validReq(array $data, array $rules, array $msgs = [], array $attrs = []) {
        return $this->validData('验证请求', $data, $rules, $msgs, $attrs);
    }

    /**
     * 验证数据.
     */
    public function validData(string $action, array $data, array $rules, array $msgs = [], array $attrs = []) {
        $validator = $this->validatorFactory->make($data, $rules, $msgs, $attrs);
        $validator->fails();
        if ($validator->fails()) {
            $errorMessage = $validator->errors()->first();
            throw new BusinessException("$action, 参数校验失败: " . $errorMessage);
        }
        return $validator->validated();
    }
}