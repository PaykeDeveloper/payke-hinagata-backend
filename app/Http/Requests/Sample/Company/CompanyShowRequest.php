<?php

// FIXME: SAMPLE CODE

namespace App\Http\Requests\Sample\Company;

use Illuminate\Http\Response;

class CompanyShowRequest extends CompanyIndexRequest
{
    protected function prepareForValidation()
    {
        parent::prepareForValidation();

        // 全ての閲覧権限を持っている場合は権限判定をスキップ
        if ($this->user()->can('viewAll_company')) {
            return;
        }

        // Company のスタッフは閲覧可能
        $data = $this->route('company');
        foreach ($data->staff as $staff) {
            if ($staff->user_id === $this->user()->id) {
                return;
            }
        }
        abort(Response::HTTP_NOT_FOUND);
    }
}