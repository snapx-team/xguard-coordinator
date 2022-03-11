<?php

namespace Xguard\Coordinator\Repositories;

use Xguard\Coordinator\Models\Coordinator;

class CoordinatorsRepository
{
    public static function findOrFail(int $id): Coordinator
    {
        return Coordinator::findOrFail($id);
    }
}
