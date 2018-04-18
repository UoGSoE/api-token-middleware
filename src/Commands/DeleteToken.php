<?php

namespace UoGSoE\ApiTokenMiddleware\Commands;

use Illuminate\Console\Command;
use App\ApiToken;

class DeleteToken extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'apitoken:delete {service}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove an API token';

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
        if (! $token) {
            $this->error('No such service');
            exit;
        }
        $token->delete();
        $this->info("Token for {$service} removed");
    }
}
