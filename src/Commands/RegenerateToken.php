<?php

namespace UoGSoE\ApiTokenMiddleware\Commands;

use Illuminate\Console\Command;
use App\ApiToken;

class RegenerateToken extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'apitoken:regenerate {service}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Regenerate an API token';

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
        if (!$token) {
            $this->error('No such service');
            exit;
        }
        $token = ApiToken::regenerate($service);

        $this->info("Token regenerated :");
        $this->table(['Service', 'Token'], [['service' => $service, 'token' => $token]]);
    }
}
