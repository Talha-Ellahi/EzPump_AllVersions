<?php
namespace App\Imports;

use App\Models\DipChartValue;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\ToModel;

class DipChartImport implements ToCollection, WithHeadingRow, WithChunkReading
{
    private $tankId;

    public function __construct($tankId)
    {
        $this->tankId = $tankId;
    }

    public function collection(Collection $collection)
    {
        // Remove previous values for the tank

        // Store dip chart values
        foreach ($collection as $row) {
            if (!isset($row['mm']) || !isset($row['liters'])) {
                continue;
            }
            $attr = [
                'tank_id' => $this->tankId,
                'millimeter' => $row['mm'],
                'liter_value' => $row['liters'],
            ];
            // dd($attr);
try {
    DipChartValue::create($attr);
} catch (\Exception $e) {
    dd($attr);
}        }
    }

    public function chunkSize(): int
    {
        return 1000;
    }
}
