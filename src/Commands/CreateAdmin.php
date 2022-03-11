<?php

namespace Xguard\Coordinator\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Xguard\Coordinator\Models\Coordinator;

class CreateAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'coordinator-app:create-admin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create Admin';

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
        $email = $this->ask('ERP email:');
        $user = User::where('email', $email) -> first();

        Coordinator::create([
            'user_id' => $user->id,
            'role' => 'admin',
        ]);
    }
}
