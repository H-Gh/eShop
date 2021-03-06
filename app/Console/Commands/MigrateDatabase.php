<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

/**
 * This class check the connection to mysql and create a loop to wait for mysql to be ready
 * PHP version >= 7.0
 *
 * @category Commands
 * @package  eShop
 * @author   Hamed Ghasempour <hamedghasempour@gmail.com>
 */
class MigrateDatabase extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = "eshop:db:migrate {--database=} {--force} {--seed}";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "A command to create a loop to check mysql connection to migrate database.";

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $commandString = $this->getCommandString();
        $now = Carbon::now();
        $counter = 0;
        while (Carbon::now()->diff($now)->s < 120) {
            try {
                ++$counter;
                $this->echoMigratingInfo($counter);
                Artisan::call($commandString);
                $this->output->success("Migration is done");
                break;
            } catch (QueryException $exception) {
                $this->output->error($exception->getMessage());
                $this->output->info("Sleep for 2 seconds ... ");
                sleep(2);
                continue;
            }
        }
    }

    /**
     * @return string
     */
    private function getCommandString(): string
    {
        $commandString = "migrate";
        if (!empty($this->option("database"))) {
            $commandString .= " --database=" . $this->option("database");
        }
        if ($this->option("force")) {
            $commandString .= " --force";
        }
        if ($this->option("seed")) {
            $commandString .= " --seed";
        }
        return $commandString;
    }

    /**
     * @param int $counter
     *
     * @return void
     */
    private function echoMigratingInfo(int $counter): void
    {
        if (!empty($this->option("database"))) {
            $connection = DB::connection($this->option("database"));
        } else {
            $connection = DB::connection();
        }
        $this->output->info("$counter# Try to migrate {$connection->getDatabaseName()} ... ");
    }
}
