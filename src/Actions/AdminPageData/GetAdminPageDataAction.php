<?php

namespace Xguard\Coordinator\Actions\AdminPageData;

use Lorisleiva\Actions\Action;
use Xguard\Coordinator\Models\Coordinator;
use Xguard\Coordinator\Enums\Roles;
use Xguard\Coordinator\Enums\SessionVariables;

class GetAdminPageDataAction extends Action
{

    const COORDINATORS = 'coordinators';

    public function authorize(): bool
    {
        if (session(SessionVariables::ROLE()->getValue()) ===  Roles::ADMIN()->getValue()) {
            return true;
        }
        return false;
    }

    public function handle(): array
    {
        $coordinators = Coordinator::with(Coordinator::USER_RELATION_NAME)->get();

        return [
            self::COORDINATORS => $coordinators,
        ];
    }
}
