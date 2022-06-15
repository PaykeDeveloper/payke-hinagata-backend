<?php

/*
|--------------------------------------------------------------------------
| Validation Language Lines
|--------------------------------------------------------------------------
|
| The following language lines contain the default error messages used by
| the validator class. Some of these rules have multiple versions such
| as the size rules. Feel free to tweak each of these messages here.
|
*/

$base = require __DIR__ . '/../../vendor/laravel-lang/lang/locales/ja/validation.php';
return array_merge($base, [
    'locking' => '別のリクエストにより更新されています。',
    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],
    'attributes' => [
        // FIXME: SAMPLE CODE
        "confirmed" => "確認済みフラグ",
        "publish_date" => "公開日",
        "approved_at" => "到達日時",
        "amount" => "数量",
        "column" => "カラム",
        "choices" => "選択肢",
        "description" => "説明",
        "votes" => "得票",
    ],
]);
