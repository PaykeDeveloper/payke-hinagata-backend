<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest as BaseClass;

// FIXME: サンプルコードです。
class FormRequest extends BaseClass
{
    protected array $casts = [];

    protected function prepareForValidation()
    {
        parent::prepareForValidation();

        $casts = $this->casts;
        if (!empty($casts)) {
            $this->castInputs($casts);
        }
    }

    /**
     * @param array $casts
     */
    private function castInputs(array $casts)
    {
        $inputs = $this->all(array_keys($casts));
        $merge_inputs = [];
        foreach ($casts as $field => $pattern) {
            if (!array_key_exists($field, $inputs)) {
                continue;
            }
            $input = $inputs[$field];
            $casted_input = $this->cast($input, $pattern);

            if ($input !== $casted_input) {
                $merge_inputs[$field] = $casted_input;
            }
        }

        if (!empty($merge_inputs)) {
            $this->merge($merge_inputs);
        }
    }

    private static function cast(mixed $input, string $pattern): mixed
    {
        switch ($pattern) {
            case 'boolean':
                if ($input === 'true') {
                    return true;
                }
                if ($input === 'false') {
                    return false;
                }
                break;
            case 'string':
                if ($input === null) {
                    return '';
                }
        }
        return $input;
    }
}
