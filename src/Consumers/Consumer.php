<?php

namespace HcSync\Consumers;

use App\Models\Employee as ModelEmployee;
use App\Models\HcSyncConfig;
use App\Models\HcSyncEvent;
use App\Models\Organization as ModelOrganization;
use App\Models\TeamWork;
use App\Models\TeamWorkMembership;
use HcSync\LoggerTrait;
use Illuminate\Support\Facades\DB;

class Consumer implements OrganizationConsumer, EmployeeConsumer, TeamworkConsumer
{
    use LoggerTrait;

    public string $hash;
    public array $hcEvent;

    public function __construct(array $hcEvent)
    {
        $this->hash = $hcEvent['hash'];
        $this->hcEvent = $hcEvent;
    }

    /**
     * @param mixed $lastHash 
     * @return self
     */
    public function setLastHash($lastHash)
    {
        return HcSyncConfig::updateOrCreate(['conf_key' => 'last_hash'], [
            'conf_key' => 'last_hash',
            'conf_value' => $lastHash
        ]);
    }

    /**
     * @param mixed $lock 
     * @return self
     */
    public function setLock($lock)
    {
        $this->info($lock ? 'Mengunci Transaksi' : 'Transaski Dibuka');
        return HcSyncConfig::updateOrCreate(['conf_key' => 'lock'], [
            'conf_key' => 'lock',
            'conf_value' => $lock
        ]);
    }

    public function insertEvent($event)
    {
        $event = collect($event)->except('created_at', 'updated_at')->toArray();
        return HcSyncEvent::insert($event);
    }

    // implement abstract

    /**
     * ORGANIZATION CONSUMER
     */
    public function organizationCreated(array $event): bool
    {
        try {
            DB::beginTransaction();

            // crate organizationCreated event
            event('organizationCreated', json_encode($event));

            //
            $data = collect($event)->except('id', 'created_at', 'updated_at')->toArray();
            $org = ModelOrganization::updateOrCreate(['code' => $data['code']], $data);

            $this->setLastHash($this->hash);
            $this->insertEvent($this->hcEvent);

            DB::commit();
            $this->success($this->hash, $this->hcEvent['name']);
            return true;
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->error($th->getMessage(), $this->hcEvent['name']);

            return false;
        }
    }

    public function organizationUpdated(array $event): bool
    {
        try {
            DB::beginTransaction();

            // crate organizationUpdated event
            event('organizationUpdated', json_encode($event));

            //
            $data = collect($event)->except('id', 'created_at', 'updated_at')->toArray();
            $org = ModelOrganization::updateOrCreate(['code' => $data['code']], $data);

            $this->setLastHash($this->hash);
            $this->insertEvent($this->hcEvent);

            DB::commit();
            $this->success($this->hash, $this->hcEvent['name']);
            return true;
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->error($th->getMessage(), $this->hcEvent['name']);

            return false;
        }
    }

    /**
     * EMPLOYEE CONSUMER
     */

    /**
     * @param array $event
     * @return bool
     */
    public function employeeCreated(array $event): bool
    {
        try {
            DB::beginTransaction();

            // crate employeeCreated event
            event('employeeCreated', json_encode($event));

            //
            $data = collect($event)->except('id', 'created_at', 'updated_at')->toArray();
            $emp = ModelEmployee::updateOrCreate(['nip' => $data['nip']], $data);

            $this->setLastHash($this->hash);
            $this->insertEvent($this->hcEvent);

            DB::commit();
            $this->success($this->hash, $this->hcEvent['name']);
            return true;
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->error($th->getMessage(), $this->hcEvent['name']);

            return false;
        }
    }

    /**
     * @param array $event
     * @return bool
     */
    public function employeeUpdated(array $event): bool
    {
        try {
            DB::beginTransaction();

            // crate employeeUpdated event
            event('employeeUpdated', json_encode($event));

            //
            $data = collect($event)->except('id', 'created_at', 'updated_at')->toArray();
            $emp = ModelEmployee::updateOrCreate(['nip' => $data['nip']], $data);

            $this->setLastHash($this->hash);
            $this->insertEvent($this->hcEvent);

            DB::commit();
            $this->success($this->hash, $this->hcEvent['name']);
            return true;
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->error($th->getMessage(), $this->hcEvent['name']);

            return false;
        }
    }

    /**
     * @param array $event
     * @return bool
     */
    public function changeOrganization(array $event): bool
    {
        try {
            DB::beginTransaction();

            // crate changeOrganization event
            event('changeOrganization', json_encode($event));

            //
            $emp = ModelEmployee::where('nip', $event['nip'])->update(['organization_code' => $event['organization_code']]);

            $this->setLastHash($this->hash);
            $this->insertEvent($this->hcEvent);

            DB::commit();
            $this->success($this->hash, $this->hcEvent['name']);
            return true;
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->error($th->getMessage(), $this->hcEvent['name']);

            return false;
        }
    }

    /**
     * @param array $event
     * @return bool
     */
    public function changePosition(array $event): bool
    {
        try {
            DB::beginTransaction();

            // crate changePosition event
            event('changePosition', json_encode($event));

            //
            $data = collect($event)->except('id', 'created_at', 'updated_at')->toArray();
            $emp = ModelEmployee::updateOrCreate(['nip' => $data['nip']], $data);

            $this->setLastHash($this->hash);
            $this->insertEvent($this->hcEvent);

            DB::commit();
            $this->success($this->hash, $this->hcEvent['name']);
            return true;
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->error($th->getMessage(), $this->hcEvent['name']);

            return false;
        }
    }

    /**
     * @param array $event
     * @return bool
     */
    public function updatePosition(array $event): bool
    {
        try {
            DB::beginTransaction();

            // crate updatePosition event
            event('updatePosition', json_encode($event));

            //
            $data = collect($event)->except('id', 'created_at', 'updated_at')->toArray();
            $emp = ModelEmployee::updateOrCreate(['nip' => $data['nip']], $data);

            $this->setLastHash($this->hash);
            $this->insertEvent($this->hcEvent);

            DB::commit();
            $this->success($this->hash, $this->hcEvent['name']);
            return true;
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->error($th->getMessage(), $this->hcEvent['name']);

            return false;
        }
    }

    /**
     * @param array $event
     * @return bool
     */
    public function employeeActivated(array $event): bool
    {
        try {
            DB::beginTransaction();

            // crate employeeActivated event
            event('employeeActivated', json_encode($event));

            //
            $emp = ModelEmployee::where('nip', $event['nip'])->update(['active' => true]);

            $this->setLastHash($this->hash);
            $this->insertEvent($this->hcEvent);

            DB::commit();
            $this->success($this->hash, $this->hcEvent['name']);
            return true;
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->error($th->getMessage(), $this->hcEvent['name']);

            return false;
        }
    }

    /**
     * @param array $event
     * @return bool
     */
    public function employeeDisabled(array $event): bool
    {
        try {
            DB::beginTransaction();

            // crate employeeDisabled event
            event('employeeDisabled', json_encode($event));

            //
            $emp = ModelEmployee::where('nip', $event['nip'])->update(['active' => false]);

            $this->setLastHash($this->hash);
            $this->insertEvent($this->hcEvent);

            DB::commit();
            $this->success($this->hash, $this->hcEvent['name']);
            return true;
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->error($th->getMessage(), $this->hcEvent['name']);

            return false;
        }
    }

    /**
     * @param array $event
     * @return bool
     */
    public function employmentStatusCreated(array $event): bool
    {
        event('employmentStatusCreated', json_encode($event));

        return true;
    }

    /**
     * @param array $event
     * @return bool
     */
    public function employmentStatusUpdated(array $event): bool
    {
        event('employmentStatusUpdated', json_encode($event));

        return true;
    }
    /**
     * @param array $event
     * @return bool
     */
    public function employmentStatusDeleted(array $event): bool
    {
        event('employmentStatusDeleted', json_encode($event));

        return true;
    }


    /**
     * TEAMWORK CONSUMER
     */

    /**
     * @param array $event
     * @return bool
     */
    public function teamworkCreated(array $event): bool
    {
        try {
            DB::beginTransaction();

            // crate teamworkCreated event
            event('teamworkCreated', json_encode($event));

            //
            $data = collect($event)->except('id', 'created_at', 'updated_at')->toArray();
            $tw = TeamWork::updateOrCreate(['code' => $data['code']], $data);

            $this->setLastHash($this->hash);
            $this->insertEvent($this->hcEvent);

            DB::commit();
            $this->success($this->hash, $this->hcEvent['name']);
            return true;
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->error($th->getMessage(), $this->hcEvent['name']);

            return false;
        }
    }

    /**
     * @param array $event
     * @return bool
     */
    public function teamworkUpdated(array $event): bool
    {
        try {
            DB::beginTransaction();

            // crate teamworkUpdated event
            event('teamworkUpdated', json_encode($event));

            //
            $data = collect($event)->except('id', 'created_at', 'updated_at')->toArray();
            $tw = TeamWork::updateOrCreate(['code' => $data['code']], $data);

            $this->setLastHash($this->hash);
            $this->insertEvent($this->hcEvent);

            DB::commit();
            $this->success($this->hash, $this->hcEvent['name']);
            return true;
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->error($th->getMessage(), $this->hcEvent['name']);

            return false;
        }
    }

    /**
     * @param array $event
     * @return bool
     */
    public function teamworkActivated(array $event): bool
    {
        try {
            DB::beginTransaction();

            // crate teamworkActivated event
            event('teamworkActivated', json_encode($event));

            //
            $data = collect($event)->except('id', 'created_at', 'updated_at')->toArray();
            $tw = TeamWork::where('code', $data['code'])->update(['active' => true]);

            $this->setLastHash($this->hash);
            $this->insertEvent($this->hcEvent);

            DB::commit();
            $this->success($this->hash, $this->hcEvent['name']);
            return true;
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->error($th->getMessage(), $this->hcEvent['name']);

            return false;
        }
    }

    /**
     * @param array $event
     * @return bool
     */
    public function teamworkDeactivated(array $event): bool
    {
        try {
            DB::beginTransaction();

            // crate teamworkDeactivated event
            event('teamworkDeactivated', json_encode($event));

            //
            $data = collect($event)->except('id', 'created_at', 'updated_at')->toArray();
            $tw = TeamWork::where('code', $data['code'])->update(['active' => false]);

            $this->setLastHash($this->hash);
            $this->insertEvent($this->hcEvent);

            DB::commit();
            $this->success($this->hash, $this->hcEvent['name']);
            return true;
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->error($th->getMessage(), $this->hcEvent['name']);

            return false;
        }
    }

    /**
     * @param array $event
     * @return bool
     */
    public function teamleaderActivated(array $event): bool
    {
        try {
            DB::beginTransaction();

            // crate teamleaderActivated event
            event('teamleaderActivated', json_encode($event));

            //
            $data = collect($event)->except('id', 'created_at', 'updated_at')->toArray();
            $twl = TeamWorkMembership::updateOrCreate(['code' => $data['code']], $data);

            $this->setLastHash($this->hash);
            $this->insertEvent($this->hcEvent);

            DB::commit();
            $this->success($this->hash, $this->hcEvent['name']);
            return true;
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->error($th->getMessage(), $this->hcEvent['name']);

            return false;
        }
    }

    /**
     * @param array $event
     * @return bool
     */
    public function teamleaderDeactivate(array $event): bool
    {
        try {
            DB::beginTransaction();

            // crate teamleaderDeactivate event
            event('teamleaderDeactivate', json_encode($event));

            //
            $data = collect($event)->except('id', 'created_at', 'updated_at')->toArray();
            $twl = TeamWorkMembership::updateOrCreate(['code' => $data['code']], $data);

            $this->setLastHash($this->hash);
            $this->insertEvent($this->hcEvent);

            DB::commit();
            $this->success($this->hash, $this->hcEvent['name']);
            return true;
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->error($th->getMessage(), $this->hcEvent['name']);

            return false;
        }
    }

    /**
     * @param array $event
     * @return bool
     */
    public function teammemberActivated(array $event): bool
    {
        try {
            DB::beginTransaction();

            // crate teammemberActivated event
            event('teammemberActivated', json_encode($event));

            //
            $data = collect($event)->except('id', 'created_at', 'updated_at')->toArray();
            $twm = TeamWorkMembership::updateOrCreate(['code' => $data['code']], $data);

            $this->setLastHash($this->hash);
            $this->insertEvent($this->hcEvent);

            DB::commit();
            $this->success($this->hash, $this->hcEvent['name']);
            return true;
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->error($th->getMessage(), $this->hcEvent['name']);

            return false;
        }
    }

    /**
     * @param array $event
     * @return bool
     */
    public function teammemberDeactivate(array $event): bool
    {
        try {
            DB::beginTransaction();

            // crate teammemberDeactivate event
            event('teammemberDeactivate', json_encode($event));

            //
            $data = collect($event)->except('id', 'created_at', 'updated_at')->toArray();
            $twm = TeamWorkMembership::updateOrCreate(['code' => $data['code']], $data);

            $this->setLastHash($this->hash);
            $this->insertEvent($this->hcEvent);

            DB::commit();
            $this->success($this->hash, $this->hcEvent['name']);
            return true;
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->error($th->getMessage(), $this->hcEvent['name']);

            return false;
        }
    }
}
