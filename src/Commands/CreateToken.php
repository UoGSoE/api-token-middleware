<?php

namespace UoGSoE\ApiTokenMiddleware\Commands;

use Illuminate\Console\Command;
use UoGSoE\ApiTokenMiddleware\ApiToken;

class CreateToken extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'apitoken:create {service}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new API token';

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
        $service = $this->argument('service');
        $token = ApiToken::where('service', '=', $service)->first();
        if ($token) {
            $this->error('That service name is already used');
            exit;
        }
        $token = ApiToken::createNew($service);

        $this->info("Token created :");
        $this->table(['Service', 'Token'], [['service' => $service, 'token' => $token]]);
    }
}
