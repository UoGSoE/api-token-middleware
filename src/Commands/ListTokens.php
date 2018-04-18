<?php

namespace UoGSoE\ApiTokenMiddleware\Commands;

use App\ApiToken;
use Illuminate\Console\Command;

class ListTokens extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'apitoken:list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List current API tokens';

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
        $this->table(['Service', 'Hashed Token'], ApiToken::all(['service', 'token']));
    }
}
