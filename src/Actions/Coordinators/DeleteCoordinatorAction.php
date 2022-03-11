<?php

namespace Xguard\Coordinator\Actions\Coordinators;

use Exception;
use Lorisleiva\Actions\Action;
use Throwable;
use Xguard\Coordinator\Models\Coordinator;

class DeleteCoordinatorAction extends Action
{

    public function rules(): array
    {
        return [
            'coordinatorId' => ['required', 'integer', 'gt:0'],
        ];
    }

    /**
     * @throws Exception|Throwable
     */
    public function handle()
    {
        try {
            \DB::beginTransaction();
            $coordinator = Coordinator::findOrFail($this->coordinatorId);
            $coordinator->delete();
            \DB::commit();
        } catch (Exception $e) {
            \DB::rollBack();
            throw $e;
        }
    }
}
