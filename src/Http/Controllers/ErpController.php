<?php

namespace Xguard\Coordinator\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Collection;
use Xguard\Coordinator\Repositories\ErpContractsRepository;
use Xguard\Coordinator\Repositories\ErpUsersRepository;

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

    public function getAllContracts(): array
    {
        return ErpContractsRepository::getAllContracts();
    }

    public function getSomeContracts($search): array
    {
        return ErpContractsRepository::getSomeContracts($search);
    }
}
