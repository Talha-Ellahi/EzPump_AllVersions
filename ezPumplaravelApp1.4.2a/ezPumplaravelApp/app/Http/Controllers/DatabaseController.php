<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use App\Helpers\DatabaseHelper;

class DatabaseController extends Controller
{
    public function dumpDatabase()
    {
        try {
            // Call the helper function to dump the database
            $filePath = DatabaseHelper::dumpDatabase();

            // Serve the file as a download response
            if (File::exists($filePath)) {
                return Response::download($filePath)->deleteFileAfterSend(true);
            }

            return response()->json(['error' => 'File not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}