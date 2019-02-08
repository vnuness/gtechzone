<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class install extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if (!base_path(file_exists('.env'))) {
            $this->error('Primeiro crie e configure o arquivo .env');
            return;
        }

        $this->call('key:generate');
        $this->call('migrate');
        $this->call('db:seed');
        $this->call('app:user');
        $this->info('Gerando as Permissões');
        $this->call('permissions');
        $this->call('storage:link');

        if ($this->confirm('Deseja criar cache da aplicação?')) {
            $this->call('config:cache');
            $this->call('route:cache');
            $this->call('view:cache');
        }
    }
}
