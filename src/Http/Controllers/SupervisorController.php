<?php

namespace Xguard\Coordinator\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Responses\JsonResponse;
use Xguard\Coordinator\Actions\Supervisors\GetSupervisorsDataAction;

class SupervisorController extends Controller
{
    public function getSupervisorsData()
    {
        try {
            return app(GetSupervisorsDataAction::class)->run();
        } catch (\Exception $e) {
            return new JsonResponse([], $e->getCode(), $e->getMessage());
        }
    }
}