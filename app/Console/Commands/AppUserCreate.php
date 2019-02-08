<?php

namespace App\Console\Commands;

use App\Models\Credentials\Roles;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class AppUserCreate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Criar usuário Sys';

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
        $this->info('Informe os dados do usuário do Sistema!');

        $user_name = $this->ask('Nome?');
        $user_email = $this->ask('Email?');
        $user_pass = $this->secret('Password?');

        $user = User::create([
            'name' => $user_name,
            'email' => $user_email,
            'password' => Hash::make($user_pass)
        ]);

        Roles::firstOrCreate(['title' => 'SysAdmin'])->users()->save($user);

        $this->info('Usuário Criado.!');
    }
}
