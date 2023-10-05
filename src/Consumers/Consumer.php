<?php

namespace HcSync\Consumers;

use App\Models\Event;
use App\Models\HcSyncConfig;
use App\Models\HcSyncEvent;
use App\Models\Organization as ModelOrganization;
use Exception;
use HcSync\LoggerTrait;
use Illuminate\Support\Facades\DB;

class Consumer implements Organization
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

            // crate organizationCreated event
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
}
