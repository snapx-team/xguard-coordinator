<?php

namespace Xguard\Coordinator\Actions\CoordinatorProfileData;

use Xguard\Coordinator\Enums\SessionVariables;
use Xguard\Coordinator\Models\Coordinator;
use Carbon;
use Lorisleiva\Actions\Action;

class GetCoordinatorProfileAction extends Action
{

    const USER_NAME = 'userName';
    const USER_STATUS = 'userStatus';
    const USER_CREATED_AT = 'userCreatedAt';
    const LANGUAGE = 'language';

    public function handle(): array
    {

        $coordinator = Coordinator::with(Coordinator::USER_RELATION_NAME)->get()->find(session(SessionVariables::COORDINATOR_ID()->getValue()));

        return [
            self::USER_NAME => $coordinator->user->full_name,
            self::USER_STATUS => $coordinator->role,
            self::USER_CREATED_AT => Carbon::parse($coordinator->created_at)->toDateString(),
            self::LANGUAGE => $coordinator->user->locale
        ];
    }
}
