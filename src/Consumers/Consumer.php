<?php

namespace HcSync\Consumers;

use App\Models\Employee;
use App\Models\HcSyncConfig;
use App\Models\HcSyncEvent;
use App\Models\Organization as ModelOrganization;
use HcSync\LoggerTrait;
use Illuminate\Support\Facades\DB;

class Consumer implements Organization, Employee
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

            // crate employeeCreated event
            event('employeeCreated', $event);

            //
            $data = collect($event)->except('id', 'created_at', 'updated_at')->toArray();
            $emp = Employee::updateOrCreate(['nip' => $data['nip']], $data);

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
            event('employeeUpdated', $event);

            //
            $data = collect($event)->except('id', 'created_at', 'updated_at')->toArray();
            $emp = Employee::updateOrCreate(['nip' => $data['nip']], $data);

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
            $emp = Employee::where('nip', $event['nip'])->update('organization_code', $event['organization_code']);

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
            $emp = Employee::updateOrCreate(['nip' => $data['nip']], $data);

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
            $emp = Employee::updateOrCreate(['nip' => $data['nip']], $data);

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
            event('employeeActivated', $event);

            //
            $emp = Employee::where('nip', $event['nip'])->update('active', true);

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
            event('employeeDisabled', $event);

            //
            $emp = Employee::where('nip', $event['nip'])->update('active', false);

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
