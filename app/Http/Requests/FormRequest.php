<?php

// FIXME: SAMPLE CODE

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest as BaseClass;

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
    private function castInputs(array $casts): void
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

    /**
     * @param mixed $input
     * @param string $pattern
     * @return mixed
     */
    private static function cast($input, string $pattern)
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
