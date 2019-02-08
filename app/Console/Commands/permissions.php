<?php

namespace App\Console\Commands;

use App\Library\PermissionBuilder;
use App\Library\PermissionControl;
use Illuminate\Console\Command;

class Permissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'permissions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gera ou atualiza as permissões do arquivo de config/permissions';


    private $control;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->control = new PermissionBuilder();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->control->updateAllPermissions();
        $this->info('Permissões atualizadas');
    }

}
