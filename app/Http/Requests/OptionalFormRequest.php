<?php

namespace App\Http\Requests;

trait OptionalFormRequest
{
    private static $separator = '|';

    private function toOptionalRules(array $rules): array
    {
        return array_map(function ($rule) {
            $values = explode(self::$separator, $rule);
            $optional_values = array_filter($values, function ($value) {
                return $value !== 'required';
            });
            return implode(self::$separator, $optional_values);
        }, $rules);
    }

    private function toOptionalRulesIfNeeded(array $rules): array
    {
        if ($this->isMethod('POST')) {
            return $rules;
        }
        return $this->toOptionalRules($rules);
    }
}
