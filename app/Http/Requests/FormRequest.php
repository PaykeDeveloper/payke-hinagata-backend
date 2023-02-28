<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest as BaseFormRequest;

class FormRequest extends BaseFormRequest
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

    private function castInputs(array $casts): void
    {
        $inputs = $this->all();
        $mergeInputs = [];
        foreach ($casts as $field => $pattern) {
            if (!array_key_exists($field, $inputs)) {
                continue;
            }
            $input = $inputs[$field];
            $castedInput = $this->cast($input, $pattern);

            if ($input !== $castedInput) {
                $mergeInputs[$field] = $castedInput;
            }
        }

        if (!empty($mergeInputs)) {
            $this->merge($mergeInputs);
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
                return preg_replace("/\r\n/", "\n", $input);
            case 'array':
                if (is_array($input) && count($input) === 1 && $input[0] === null) {
                    return [];
                }
                break;
        }
        return $input;
    }
}
