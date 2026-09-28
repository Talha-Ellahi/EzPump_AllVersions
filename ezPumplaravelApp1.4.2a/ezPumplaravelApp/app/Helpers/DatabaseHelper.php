<?php
namespace App\Helpers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Log;
class DatabaseHelper
{
    public static function dumpDatabase(): string
    {
        // Define the output file path and name
        $filename = 'database_dump_' . date('Y_m_d_H_i_s') . '.sql';
        $filePath = storage_path($filename);

        // Retrieve database configurations
        $dbHost = env('DB_HOST');
        $dbName = env('DB_DATABASE');
        $dbUser = env('DB_USERNAME');
        $dbPassword = env('DB_PASSWORD');

        // Construct the mysqldump command
        $command = sprintf(
            'mysqldump --user=%s --password=%s --host=%s %s > %s',
            escapeshellarg($dbUser),
            escapeshellarg($dbPassword),
            escapeshellarg($dbHost),
            escapeshellarg($dbName),
            escapeshellarg($filePath)
        );

        // Execute the command
        exec($command, $output, $returnVar);

        // Check for errors
        if ($returnVar !== 0) {
Log::error('Database dump failed, command: ' . $command . '. Output: ' . print_r($output, true));
return '';
        }
// Get the directory and the file name separately
$fileDirectory = dirname($filePath);
$fileBaseName = basename($filePath);
        // Construct the tar command
        $tarFileName = $filename . '.tar.gz';
        $tarFilePath = storage_path($tarFileName);
        $tarCommand = sprintf(
            'tar -czf %s -C %s %s',
            escapeshellarg($tarFilePath),
            escapeshellarg($fileDirectory),
            escapeshellarg($fileBaseName)
        );

        // Execute the tar command
        exec($tarCommand, $tarOutput, $tarReturnVar);
        // Check for errors
        if ($tarReturnVar !== 0) {
            throw new \Exception('Zip command failed');
        }

        // Remove the SQL file after zipping
        File::delete($filePath);

        return $tarFilePath;

        return $filePath;
    }
}
