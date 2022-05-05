<?php

namespace Xguard\Coordinator\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Support\Collection;
use Xguard\Coordinator\Repositories\ErpContractsRepository;
use Xguard\Coordinator\Repositories\ErpUsersRepository;
use Xguard\Coordinator\Repositories\OnSiteEmployeeRepository;

/**
 * Class ErpController
 * @package Xguard\Coordinator\Http\Controllers
 * @group Coordinator Plugin
 */
class ErpController extends Controller
{

    public function getAllUsers(): Collection
    {
        return ErpUsersRepository::getAllUsers();
    }

    public function getSomeUsers($search): Collection
    {
        return ErpUsersRepository::getSomeUsers($search);
    }

    /**
     * Get Active Contracts
     * @responseFile public/vendor/xguard-coordinator/scribeResponses/getActiveContracts.json
     */
    public function getAllActiveContracts(): array
    {
        return ErpContractsRepository::getAllActiveContracts();
    }

    /**
     * Get Active Contracts With Search Term
     * @responseFile public/vendor/xguard-coordinator/scribeResponses/getActiveContracts.json
     */
    public function getSomeActiveContracts($search): array
    {
        return ErpContractsRepository::getSomeActiveContracts($search);
    }

    /**
     * Get On Site Employees
     * @responseFile public/vendor/xguard-coordinator/scribeResponses/getOnSiteEmployees.json
     */
    public function getOnSiteEmployees(int $id, bool $isPrimaryAddress)
    {
        return OnSiteEmployeeRepository::getOnSiteEmployees($id, $isPrimaryAddress);
    }
}
