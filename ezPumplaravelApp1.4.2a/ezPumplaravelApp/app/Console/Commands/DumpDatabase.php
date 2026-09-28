<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Helpers\DatabaseHelper;

class DumpDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:dump';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Dump the database to a file';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            // Get the optional path or default to storage
            $pathOption = '';
            $filePath = DatabaseHelper::dumpDatabase();

            // If a custom path is provided, move the file there
            if ($pathOption) {
                $customPath = rtrim($pathOption, '/') . '/' . basename($filePath);
                if (!@rename($filePath, $customPath)) {
                    $this->error('Failed to move the dump file to the specified path.');
                    return 1;
                }
                $filePath = $customPath;
            }

            $this->info("Database dumped successfully to: $filePath");
            return 0;
        } catch (\Exception $e) {
            $this->error("Error: " . $e->getMessage());
            return 1;
        }
    }
}
