<?php

namespace Xguard\Coordinator\Enums;

use MyCLabs\Enum\Enum;

/**
 * @method static SessionVariables COORDINATOR_ID()
 * @method static SessionVariables ROLE();
 */

class SessionVariables extends Enum
{
    private const COORDINATOR_ID = 'coordinator_id';
    private const ROLE = 'role';
}
