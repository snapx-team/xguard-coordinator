<?php

namespace Xguard\Coordinator\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Responses\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Xguard\Coordinator\Actions\AdminPageData\GetAdminPageDataAction;
use Xguard\Coordinator\Enums\SessionVariables;
use Xguard\Coordinator\Models\Coordinator;
use Xguard\Coordinator\Actions\CoordinatorProfileData\GetCoordinatorProfileAction;

class AppController extends Controller
{
    public function getIndex()
    {
        $this->setCoordinatorAppSessionVariables();
        return view('Xguard\Coordinator::index');
    }

    public function setCoordinatorAppSessionVariables()
    {
        $strIsLoggedIn = 'is_logged_in';
        if (Auth::check()) {
            $coordinator = Coordinator::where(Coordinator::USER_ID, '=', Auth::user()->id)->first();
            session([SessionVariables::ROLE()->getValue() => $coordinator->role, SessionVariables::COORDINATOR_ID()->getValue() => $coordinator->id]);
            return [$strIsLoggedIn => true];
        }
        return [$strIsLoggedIn => false];
    }

    public function getRoleAndCoordinatorId(): array
    {
        return [
            SessionVariables::ROLE()->getValue() => session(SessionVariables::ROLE()->getValue()),
            SessionVariables::COORDINATOR_ID()->getValue() => session(SessionVariables::COORDINATOR_ID()->getValue()),
        ];
    }

    public function getFooterInfo(): array
    {
        return [
            'parent_name' => config('coordinator_app.parent_name'),
            'version' => config('coordinator_app.version'),
            'date' => date("Y")
        ];
    }

    public function getAdminPageData()
    {
        try {
            return app(GetAdminPageDataAction::class)->run();
        } catch (\Exception $e) {
            return new JsonResponse([], $e->getCode(), $e->getMessage());
        }
    }

    public function getDashboardData()
    {
        try {
            return app(GetDashbaordDataAction::class)->run();
        } catch (\Exception $e) {
            return new JsonResponse([], $e->getCode(), $e->getMessage());
        }
    }

    public function getCoordinatorProfile()
    {
        try {
            $profile = (new GetCoordinatorProfileAction())->run();
            return new JsonResponse($profile);
        } catch (\Exception $e) {
            return new JsonResponse([], $e->getCode(), $e->getMessage());
        }
    }
}
