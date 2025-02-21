<?php

namespace App\Controllers;

use App\Exceptions\ApplicationException;
use App\Models\User;
use App\Requests\BankAccountSendRequest;
use Common\Middlewares\CSRFMiddleware;
use Illuminate\Database\Capsule\Manager as Capsule;

class BankController
{
    public function homeView(): void
    {
        checkAuth();
        /** @var User $currentUser */
        $currentUser = currentUser();

        view(__DIR__ . "/../views/bank/home.html", [
            'balance' => $currentUser->balance,
            'csrfToken' => CSRFMiddleware::getToken()
        ]);
    }

    public function bankAccountSend(BankAccountSendRequest $request): void
    {
        checkAuth();
        /** @var User $currentUser */
        $currentUser = currentUser();
        $validated = $request->getValidated();
        $amount = $validated['amount'];
        $targetUser = User::find($validated['accountId']);

        if (!$targetUser) {
            throw new ApplicationException("Target user not found");
        }

        if ($currentUser->id === $targetUser->id) {
            throw new ApplicationException("You can't send send to yourself");
        }

        if ($currentUser->balance < $amount) {
            throw new ApplicationException("Insufficient balance");
        }

        Capsule::transaction(static function () use ($currentUser, $targetUser, $amount) {
            $currentUser->balance -= $amount;
            $targetUser->balance += $amount;
            $currentUser->save();
            $targetUser->save();
        });

        redirect('bank/home');
    }
}