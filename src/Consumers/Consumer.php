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

class Consumer implements Organization, Employee, Teamwork
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
            event('organizationCreated', $event);

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
            event('organizationUpdated', $event);

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

            // crate ModelEmployeeCreated event
            event('ModelEmployeeCreated', $event);

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

            // crate ModelEmployeeUpdated event
            event('ModelEmployeeUpdated', $event);

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
            event('changeOrganization', $event);

            //
            $emp = ModelEmployee::where('nip', $event['nip'])->update('organization_code', $event['organization_code']);

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
            event('changePosition', $event);

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
            event('updatePosition', $event);

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

            // crate ModelEmployeeActivated event
            event('ModelEmployeeActivated', $event);

            //
            $emp = ModelEmployee::where('nip', $event['nip'])->update('active', true);

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

            // crate ModelEmployeeDisabled event
            event('ModelEmployeeDisabled', $event);

            //
            $emp = ModelEmployee::where('nip', $event['nip'])->update('active', false);

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
            event('teamworkCreated', $event);

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
            event('teamworkUpdated', $event);

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
            event('teamworkActivated', $event);

            //
            $data = collect($event)->except('id', 'created_at', 'updated_at')->toArray();
            $tw = TeamWork::where('code', $data['code'])->update('active', true);

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
            event('teamworkDeactivated', $event);

            //
            $data = collect($event)->except('id', 'created_at', 'updated_at')->toArray();
            $tw = TeamWork::where('code', $data['code'])->update('active', false);

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
            event('teamleaderActivated', $event);

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
            event('teamleaderDeactivate', $event);

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
            event('teammemberActivated', $event);

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
            event('teammemberDeactivate', $event);

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
