<?php

namespace Xguard\Coordinator\Enums;

use MyCLabs\Enum\Enum;

/**
 * @method static Roles ADMIN()
 * @method static Roles COORDINATOR();
 */

class Roles extends Enum
{
    private const ADMIN = 'admin';
    private const COORDINATOR = 'coordinator';
}
