<?php

namespace App\Observers;

use App\Models\saledata;
use App\Models\Shift;

class SaleDataObserver
{
    /**
     * Handle the saledata "updated" event.
     *
     * @param  \App\Models\saledata  $saledata
     * @return void
     */
    public function updated(saledata $saledata)
    {
        // if ($saledata->wasChanged('bIsUpdated') && $saledata->bIsUpdated && !$saledata->getOriginal('bIsUpdated')) {
        // \Log::info('SaleDataObserver triggered!'); // Add this line
        // \Log::info('saledata id: ' . $saledata->id); // Add this line

        $shiftId = $saledata->shift_id;
        $paymentMethodId = $saledata->p_mode;
        $qty = $saledata->qty;
        $totalSale = $saledata->amt;

        $shift = Shift::findOrFail($shiftId);

        if ($shift) {
            $shift->addSale($paymentMethodId, $qty, $totalSale);
        }
        // }
    }
}
