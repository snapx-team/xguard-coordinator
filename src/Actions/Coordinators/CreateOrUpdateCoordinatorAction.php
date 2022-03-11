<?php

namespace Xguard\Coordinator\Actions\Coordinators;

use DB;
use Lorisleiva\Actions\Action;
use Throwable;
use Xguard\Coordinator\Models\Coordinator;

class CreateOrUpdateCoordinatorAction extends Action
{
    public function rules(): array
    {
        return [
            "selectedUsers" => ['present', 'array'],
            "role" => ['required', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'selectedUsers.present' => 'No selected users',
            'role.required' => 'Coordinator role is required',
        ];
    }

    /**
     * @throws Throwable
     */
    public function handle()
    {
        try {
            DB::beginTransaction();
            foreach ($this->selectedUsers as $user) {
                $coordinator = Coordinator::updateOrCreate(
                    [Coordinator::USER_ID => $user['id']],
                    [Coordinator::ROLE => $this->role]
                );
            }
            DB::commit();
            return $coordinator;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
