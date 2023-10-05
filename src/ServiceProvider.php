<?php

namespace HcSync;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use HcSync\Commands\LumenPublish;
use LaravelSbagio\Commands\Run;

class ServiceProvider extends BaseServiceProvider
{
    /**
     * @throws \Exception
     */
    public function boot()
    {
        if (!(file_exists(app_path('User.php')) || !file_exists('Models/User.php'))) {
            throw new \Exception('Silahkan menjalankan `artisan make:auth` terlebih dahulu');
        }

        $this->loadMigrations();

        $dir = dirname(__FILE__) . '/..';
        $copyMap = collect([]);

        if (!file_exists(app_path('Models/Organization.php'))) {
            $copyMap[$dir . '/models/Organization.php'] = app_path('Models/Organization.php');
        }

        if (!file_exists(app_path('Models/Pegawai.php'))) {
            $copyMap[$dir . '/models/Pegawai.php'] = app_path('Models/Pegawai.php');
        }

        if (!file_exists(app_path('Models/HcSyncEvent.php'))) {
            $copyMap[$dir . '/models/HcSyncEvent.php'] = app_path('Models/HcSyncEvent.php');
        }

        if (!file_exists(app_path('Models/HcSyncConfig.php'))) {
            $copyMap[$dir . '/models/HcSyncConfig.php'] = app_path('Models/HcSyncConfig.php');
        }

        if (!file_exists(app_path('Models/TimKerja.php'))) {
            $copyMap[$dir . '/models/TimKerja.php'] = app_path('Models/TimKerja.php');
        }

        if (!file_exists(app_path('Models/TimKerjaMembership.php'))) {
            $copyMap[$dir . '/models/TimKerjaMembership.php'] = app_path('Models/TimKerjaMembership.php');
        }

        if ($copyMap->isNotEmpty()) {
            $this->publishes($copyMap->toArray());
        }

        if ($this->app->runningInConsole()) {
            $this->commands([
                Run::class,
            ]);
        }
    }

    public function register()
    {
    }

    public function loadMigrations()
    {
        $dir = dirname(__FILE__) . '/..';
        $copyMap = collect([]);

        $orgMigrationFile = glob(database_path('migrations/*create_organizations_table*'));
        if (count($orgMigrationFile) <= 0) {
            $timestamp = date('Y_m_d_His', time());
            $copyMap[$dir . '/migrations/create_organizations_tables.php'] = database_path('migrations/' . $timestamp . '_create_organizations_tables.php');
        }
        $empMigrationFile = glob(database_path('migrations/*create_employees_table*'));
        if (count($empMigrationFile) <= 0) {
            $timestamp = date('Y_m_d_His', time());
            $copyMap[$dir . '/migrations/create_employees_tables.php'] = database_path('migrations/' . $timestamp . '_create_employees_tables.php');
        }
        $twMigrationFile = glob(database_path('migrations/*create_team_works_table*'));
        if (count($twMigrationFile) <= 0) {
            $timestamp = date('Y_m_d_His', time());
            $copyMap[$dir . '/migrations/create_team_works_table.php'] = database_path('migrations/' . $timestamp . 'create_team_works_table.php');
        }
        $twmMigrationFile = glob(database_path('migrations/*create_team_work_memberships_table*'));
        if (count($twmMigrationFile) <= 0) {
            $timestamp = date('Y_m_d_His', time());
            $copyMap[$dir . '/migrations/create_team_work_memberships_table.php'] = database_path('migrations/' . $timestamp . 'create_team_work_memberships_table.php');
        }
        $hccMigrationFile = glob(database_path('migrations/*create_hc_sync_configs_table*'));
        if (count($hccMigrationFile) <= 0) {
            $timestamp = date('Y_m_d_His', time());
            $copyMap[$dir . '/migrations/create_hc_sync_configs_table.php'] = database_path('migrations/' . $timestamp . 'create_hc_sync_configs_table.php');
        }
        $hceMigrationFile = glob(database_path('migrations/*create_hc_sync_events_table*'));
        if (count($hceMigrationFile) <= 0) {
            $timestamp = date('Y_m_d_His', time());
            $copyMap[$dir . '/migrations/create_hc_sync_events_table.php'] = database_path('migrations/' . $timestamp . 'create_hc_sync_events_table.php');
        }

        if ($copyMap->isNotEmpty()) {
            $this->publishes($copyMap->toArray());
        }
    }
}
