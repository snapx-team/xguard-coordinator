<?php

namespace Xguard\Coordinator\Repositories;

use App\Models\Contract;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Xguard\Coordinator\Http\Resources\ContractResource;
use Xguard\LaravelKanban\Entities\ErpContract;

class ErpContractsRepository
{
    public static function retrieve(int $erpContractId): ?ErpContract
    {
        $erpContract = Contract::find($erpContractId);
        return $erpContract ? new ErpContract($erpContract->id, $erpContract->contract_identifier) : null;
    }

    public static function getAllContracts(): AnonymousResourceCollection
    {
        $erpContracts = Contract::with('jobSite')->orderBy('contract_identifier')->get();

        return ContractResource::collection($erpContracts);
    }

    public static function getSomeContracts($search): AnonymousResourceCollection
    {
        $erpContracts = Contract::with('jobSite')->where(function ($q) use ($search) {
            $q->where('contract_identifier', 'like', "%{$search}%");
        })->orderBy('contract_identifier')->take(10)->get();

        return ContractResource::collection($erpContracts);
    }
}
