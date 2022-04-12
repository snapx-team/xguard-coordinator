<?php

namespace Xguard\Coordinator\Repositories;

use App\Models\Contract;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Xguard\LaravelKanban\Entities\ErpContract;

class ErpContractsRepository
{
    public static function retrieve(int $erpContractId): ?ErpContract
    {
        $erpContract = Contract::find($erpContractId);
        return $erpContract ? new ErpContract($erpContract->id, $erpContract->contract_identifier) : null;
    }

    public static function getAllContracts(): array
    {
        $erpContracts = Contract::with('jobSite.subaddresses')->orderBy('contract_identifier')->get();
        return self::formatContracts($erpContracts);
    }

    public static function getSomeContracts($search): array
    {
        $erpContracts = Contract::with('jobSite')->where(function ($q) use ($search) {
            $q->where('contract_identifier', 'like', "%{$search}%");
        })->orderBy('contract_identifier')->take(10)->get();

        return self::formatContracts($erpContracts);
    }

    public static function formatContracts($erpContracts)
    {
        return $erpContracts->map(function ($erpContract) {

            $erpContract->addresses = collect([]);
            $erpContract->addresses->push([
                'address' => $erpContract->jobSite->google_formatted_address,
                'lat' => $erpContract->jobSite->google_coordinates_lat,
                'lng' => $erpContract->jobSite->google_coordinates_lng
            ]);
            foreach ($erpContract['jobSite']['subaddresses'] as $subaddress) {
                $erpContract->addresses->push([
                    'address' => $subaddress->formatted_address,
                    'lat' => $subaddress->latitude,
                    'lng' => $subaddress->longitude,
                ]);
            }

            return [
                'id' => $erpContract->id,
                'name' => $erpContract->contract_identifier,
                'jobSiteImagePath' => $erpContract->job_site_image_path,
                'onSiteClientContactName' => $erpContract->on_site_client_info_contact_name,
                'onSiteClientContactPhone' => $erpContract->on_site_client_info_phone_number,
                'onSiteClientPosition' => $erpContract->on_site_client_info_position,
                'onSiteClientEmail' => $erpContract->on_site_client_info_email,
                'clientNeeds' => $erpContract->client_info_needs,
                'clientType' => $erpContract->client_type,
                'jobSiteType' => $erpContract->job_site_type,
                'workEnvironment' => $erpContract->work_environment,
                'addedXguardServices' => $erpContract->added_xguard_services,
                'accessToEstablishment' => $erpContract->access_to_establishment,
                'typeOfClothing' => $erpContract->type_of_clothing,
                'addresses' => $erpContract->addresses->toArray()
            ];
        })->all();
    }
}
