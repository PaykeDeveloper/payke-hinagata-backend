<?php

// FIXME: SAMPLE CODE

namespace App\Http\Requests\Sample\Project;

use Illuminate\Http\Response;

class ProjectShowRequest extends CompanyIndexRequest
{
    protected function prepareForValidation()
    {
        parent::prepareForValidation();

        // 全ての閲覧権限を持っている場合は権限判定をスキップ
        if ($this->user()->can('viewAll_company')) {
            return;
        }

        $data = $this->route('company');
        if ($data->user->id !== $this->user()->id) {
            abort(Response::HTTP_NOT_FOUND);
        }
    }
}
