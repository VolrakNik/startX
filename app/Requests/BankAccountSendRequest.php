<?php

namespace App\Requests;

use Common\Requests\Request;

class BankAccountSendRequest extends Request
{
    public function rules(): array
    {
        return [
            'amount' => ['required', 'numeric', 'min:1', 'max:1000'],
            'accountId' => ['required', 'integer'],
        ];
    }
}