<?php

namespace HcSync;

use App\Models\HcSyncConfig;
use Exception;
use HcSync\Consumers\Consumer;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Throwable;

class HumanCapital
{
    use LoggerTrait;
    private $apiToken;
    private $tokenType;
    private $config;

    public function __construct()
    {
        $this->loadConfig();

        // get api token
        $apiToken = Storage::disk('local')->get('token/access-token.txt');

        if ($apiToken) {
            $this->setApiToken($apiToken);
        } else {
            $this->auth();
        }
    }

    public function auth()
    {
        try {
            // TODO change credential with config
            $res = Http::post('http://localhost:8081/api/auth/login', ['email' => 'superuser@reksa.co.id', 'password' => 'password']);
            $jsonData = $res->json();

            if ($jsonData['status']) {
                $this->setApiToken($jsonData['data']['access_token']);
                $this->setTokenType($jsonData['data']['token_type']);
            } else {
                throw new Exception($jsonData['message']);
            }
        } catch (Throwable $th) {
            $this->error($th->getMessage());
        }
    }
    public function sync()
    {
        try {
            $res = Http::withToken($this->getApiToken())
                ->accept('application/json')
                ->get('http://localhost:8081/api/event/fetch', ['last_hash' => '']);
            $response = $res->json();
            if ($res->successful()) {
                foreach ($response['data'] as $key => $event) {
                    $data = collect(json_decode($event['data']));


                    $consumer = new Consumer($event);
                    // mengunci transaksi
                    if ($key == 0) {
                        $consumer->setLock(true);
                    }

                    // sync proccess
                    $sync = $consumer->{$event['name']}($data->toArray());
                    if (!$sync) {
                        throw new Exception("Sinkronisasi gagal dilakukan.");
                    }

                    // membuka kuncu transaksi
                    if ($key == count($response) - 1) {
                        $this->info('Sinkronisasi Berhasil', 'HC-SYNC');
                        $consumer->setLock(false);
                    }
                }
            } else {
                throw new Exception($response['message']);
            }
        } catch (\Throwable $th) {
            $this->unlock();
            $this->error($th->getMessage());
        }
    }

    public function loadConfig()
    {
        $this->config = config('hc-sync.api');
    }

    /**
     * @return mixed
     */
    public function getApiToken()
    {
        return $this->apiToken;
    }

    /**
     * @param mixed $apiToken 
     * @return self
     */
    public function setApiToken($apiToken): self
    {
        // store access token to storage
        Storage::disk('local')->put('token/access-token.txt', $apiToken);
        $this->apiToken = $apiToken;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTokenType()
    {
        return $this->tokenType;
    }

    /**
     * @param mixed $tokenType 
     * @return self
     */
    public function setTokenType($tokenType): self
    {
        $this->tokenType = $tokenType;
        return $this;
    }

    public function unlock()
    {
        $this->info('Transaski Dibuka');
        return HcSyncConfig::updateOrCreate([
            'conf_key' => 'lock',
            'conf_value' => false
        ]);
    }
}
