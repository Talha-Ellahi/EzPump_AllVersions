**TankShiftController Documentation**

This document provides details on the `closeShift` and `getTankShiftLogs` methods within the `TankShiftController.php` file.

**1. closeShift Method**

*   **Purpose:**
    Closes a tank shift, records the shift log, and initiates a new shift for the same tank.

*   **Parameters:**
    *   `Request $request`:  The HTTP request object.
    *   `int $id`: The ID of the `TankShift` to be closed.

*   **Return Type:**
    `\Illuminate\Http\JsonResponse`

*   **Closing Shift Logic:**
    1.  **Retrieve TankShift:** Finds the `TankShift` record using the provided `$id`.
    2.  **Update End Time:** Sets the `end_time` of the shift to the current time using `Carbon::now()`.
    3.  **Fetch Last Fuel Status:** Retrieves the most recent fuel status for the tank using `getLastFuelStatus($shift->tank_id)`. This fetches data from the `LastFuelStatus` table to get the latest fuel level and quantity.
    4.  **Calculate Sales Total:** Calculates the total quantity of fuel sold during the shift. It queries the `SaleData` model, filtering by `pos_id` (nozzle IDs associated with the tank) and `tdate` (shift start and end times). The `sum('qty')` aggregates the total sales quantity.
    5.  **Calculate Positive Stock:** Calculates the total positive stock changes (fuel added) during the shift. It queries the `tank_stock_ledger` table, filtering by `tank_id` and `created_at` (shift start and end times), and sums the `stock_change` where it's positive.
    6.  **Calculate Manual Closing Totalizer:**  Calculates the expected manual closing totalizer using the formula: `opening_totalizer - salesTotal - positiveStock`. This value represents the theoretical fuel level after accounting for sales and stock additions.
    7.  **Update Shift Record:** Updates the `TankShift` record in the database with the following closing details:
        *   `closing_totalizer`:  The actual closing totalizer reading, obtained from `LastFuelStatus->Qty`.
        *   `closing_mm`: The closing fuel level in millimeters, obtained from `LastFuelStatus->Level`.
        *   `manual_closing_totalizer`: The calculated manual closing totalizer.
    8.  **Prepare Shift Log Data:**  Prepares an array `$logData` to store in `TankShiftLog`.
        *   Copies all attributes from the `$shift` object into `$shiftData` array using `$shift->toArray()` and then unsets the 'id' to avoid duplication or conflicts when creating a new log entry.
        *   Retrieves `stock_ledger` entries that occurred during the shift period from the `tank_stock_ledger` table.
        *   Retrieves `sales_data` for the shift period, grouped by payment mode (`p_mode`). This provides a breakdown of sales by different payment methods.
        *   Merges `$shiftData`, `stock_ledger`, and `sales_data` into the `$logData` array, structuring it to be stored in the `data` column of the `TankShiftLog` model as JSON. Also adds 'end_time' to the log data.
    9.  **Create TankShiftLog:** Creates a new record in the `tank_shift_logs` table using the `$logData`. This archives the completed shift information.
    10. **Delete TankShift:** Deletes the original `TankShift` record with the given `$id` from the `tank_shifts` table, as the shift is now closed and its data is archived in `tank_shift_logs`.
    11. **Create New TankShift:** Creates a new `TankShift` record to start a new shift immediately. It uses the `closing_totalizer` and `closing_mm` from the just-closed shift as the `opening_totalizer` and `opening_mm` for the new shift. This ensures continuous shift management.

*   **Data Flow for TankShiftLog:**
    *   A `TankShiftLog` record is created each time a `TankShift` is closed using the `closeShift` method.
    *   The `TankShiftLog` archives comprehensive data related to the closed shift, including:
        *   Basic shift details: `tank_id`, `product_id`, `user_id`, `start_time`, `end_time`, `opening_totalizer`, `closing_totalizer`, levels in mm, and manual totalizers.
        *   `data` column (JSON format):
            *   `stock_ledger`: An array of records from the `tank_stock_ledger` table, detailing all stock changes (additions and removals) during the shift.
            *   `sales_data`: An array of sales records from the `saledata` table, aggregated by payment mode, showing the total quantity and amount of sales for each payment type during the shift.

*   **How to Close a Shift:**
    *   To initiate the closing process for a tank shift, you need to call the `closeShift` method, providing the specific `TankShift ID` as a parameter.
    *   In a typical application setup, this method would be invoked through an API endpoint. For instance, a `POST` request to an endpoint like `/tank-shifts/{id}/close` would trigger the `closeShift` action in the controller.
    *   Before calling `closeShift`, ensure that necessary validations are in place, such as checking for shift duration, user permissions, and any other business rules that dictate shift closure eligibility.

**2. getTankShiftLogs Method**

*   **Purpose:**
    Retrieves tank shift logs from the database, with optional filtering by tank ID and date range.

*   **Parameters:**
    *   `Request $request`: The HTTP request object, which may contain filter parameters.

*   **Return Type:**
    `\Illuminate\Http\JsonResponse`

*   **How Tank Shift Logs are Retrieved and Filtered:**
    1.  **Validation:** Validates the incoming request parameters to ensure that `tank_id`, `start_date`, and `end_date` are in the expected format and are valid values (e.g., `tank_id` exists in the `tanks` table, dates are valid date formats).
    2.  **Query Initialization:** Starts building a database query to fetch `TankShiftLog` records using `TankShiftLog::query()`. This creates a query builder instance, allowing for条件式 filtering and ordering.
    3.  **Filtering by Tank ID:** Checks if the request includes the `tank_id` parameter and if it's filled (not null or empty). If so, it adds a `where('tank_id', $request->tank_id)` clause to the query. This條件式 filters the logs to only include those associated with the specified tank ID.
    4.  **Filtering by Date Range:** Handles date-based filtering:
        *   Checks if both `start_date` and `end_date` are provided and filled in the request. If so, it filters logs to include only those where the `start_time` falls within the inclusive date range using `whereDate('start_time', '>=', $request->start_date)` and `whereDate('start_time', '<=', $request->end_date)`.
        *   If only `start_date` is provided and filled, it filters logs to include only those for the specific date using `whereDate('start_time', '=', $request->start_date)`.
    5.  **Ordering Results:** Orders the query results in descending order of `start_time` using `orderBy('start_time', 'desc')`. This ensures that the most recent shift logs are listed first.
    6.  **Query Execution:** Executes the built query using `->get()`. This retrieves a collection of `TankShiftLog` objects from the database that match all applied filters and ordering.
    7.  **Response Formatting:** Returns a JSON response with the following structure:
        *   `status: 'success'` to indicate that the request was processed successfully.
        *   `tank_shift_logs`: An array containing the retrieved `TankShiftLog` objects. This array will be empty if no logs match the provided filter criteria.

*   **Usage Examples:**
    *   **Get all shift logs for tank ID 1:**
        `GET /tank-shift-logs?tank_id=1`
    *   **Get shift logs for a specific date (2025-03-10):**
        `GET /tank-shift-logs?start_date=2025-03-10`
    *   **Get shift logs within a date range (from 2025-03-01 to 2025-03-15):**
        `GET /tank-shift-logs?start_date=2025-03-01&end_date=2025-03-15`

**3. shiftPrint Method**

*   **Purpose:**
    Prepares data for and renders the `shift_print.blade.php` view, which displays a tank shift report.

*   **Parameters:**
    *   `Request $request`: The HTTP request object, containing shift log IDs to be printed.

*   **Return Type:**
    `\Illuminate\View\View`

*   **Shift Print Report Logic:**
    1.  **Retrieve Log IDs:** Extracts the comma-separated string of `TankShiftLog` IDs from the request parameter `ids`.
    2.  **Explode IDs:** Converts the comma-separated string of IDs into an array of individual IDs using `explode(',', $request->ids)`.
    3.  **Fetch TankShiftLogs:** Retrieves `TankShiftLog` records from the database that match the IDs in the `$ids` array. It uses `TankShiftLog::whereIn('id', $ids)->get()` to efficiently fetch all specified logs.
    4.  **Prepare Data for View:**  The retrieved `$tankShiftLogs` collection is passed to the `tank.shift_print` view using `compact('tankShiftLogs')`.
    5.  **Render View:** Returns the `tank.shift_print` view. Laravel's view rendering engine then processes `shift_print.blade.php`, making the `$tankShiftLogs` data available within the view.

*   **View (`shift_print.blade.php`) Data Processing and Column Logic:**
    *   The `shift_print.blade.php` file iterates through each `$shiftLog` in the `$tankShiftLogs` collection to display individual shift log details in a table format.
    *   **Serial No.:** Displays the `$shiftLog->id`, which is the unique identifier for each shift log record.
    *   **Product:** Displays the `$tank->tank_name` which is retrieved using  `$tank = \App\Models\Tank::find($shiftLog->tank_id);`. This shows the name of the tank associated with the shift.
    *   **Previous (mm):** Displays `$shiftLog->opening_mm / 100`. This is the opening fuel level in millimeters, converted to a more readable unit (e.g., centimeters or kept as millimeters depending on desired precision).
    *   **Previous (Litres):** Displays `$shiftLog->opening_totalizer / 100`. This is the opening fuel volume in liters, converted from a smaller unit (likely liters * 100 in database for precision).
    *   **Purchase (Litres):** Calculates and displays the total fuel purchased (credits) during the shift.
        *   Initializes `$credits = 0`.
        *   Iterates through `$shiftLog->data['stock_ledger']` entries.
        *   For each entry with a positive `stock_change` (fuel added), it adds `$ledgerEntry['stock_change'] * 100` to `$credits`. The multiplication by 100 and later division by 100 in the view likely handles unit conversions consistently with other volume/level displays.
        *   Finally displays `number_format($credits / 100, 2)` to show the total purchase volume in liters, formatted to two decimal places.
    *   **Sales (Litres):** Calculates and displays the total fuel sold (debits) during the shift.
        *   Initializes `$debits = 0`.
        *   Iterates through `$shiftLog->data['sales_data']` entries.
        *   For each sale entry, adds the absolute value of `$sale['total_quantity']` to `$debits`.
        *   Finally displays `number_format($debits / 100, 2)` to present the total sales volume in liters, formatted to two decimal places.
    *   **Current (mm):** Displays `$shiftLog->closing_mm / 100`. This is the closing fuel level in millimeters, converted to a readable unit, similar to "Previous (mm)".
    *   **Current (Litres):** Displays `$shiftLog->closing_totalizer / 100`. This is the closing fuel volume in liters, converted from a smaller unit, similar to "Previous (Litres)".
    *   **Expected (Litres):** Displays `$shiftLog->manual_closing_totalizer / 100`. This is the calculated manual closing totalizer volume in liters, representing the expected fuel volume after sales and purchases.
    *   **Shortage:** Calculates and displays the difference between the actual closing totalizer and the manual closing totalizer (`$difference = $shiftLog->closing_totalizer - $shiftLog->manual_closing_totalizer`).
        *   The background color of this cell is dynamically set based on the `$difference` value:
            *   Red if `$difference < 0` (shortage/loss).
            *   Green if `$difference > 0` (gain/surplus).
            *   Yellow if `$difference == 0` (perfect balance).
        *   Displays `number_format($difference / 100, 2)` to show the shortage/surplus volume in liters, formatted to two decimal places.
    *   **Loss Type:** Currently hardcoded to `0`. This column is likely intended for future implementation to categorize different types of fuel loss (e.g., evaporation, theft, leakage), but is not yet functional in the provided code.

*   **How to Access Shift Print Report:**
    *   The `shiftPrint` method is designed to be accessed via a web route, typically a `GET` request.
    *   The route should pass the required `ids` parameter, which is a comma-separated list of `TankShiftLog` IDs that you want to include in the report.
    *   For example, a route definition might look like: `Route::get('/tank-shift-print/{ids}', [TankShiftController::class, 'shiftPrint']);`
    *   To view the report, you would navigate to the URL associated with this route in a web browser, providing the `ids` in the URL. For instance: `/tank-shift-print/1,2,3` would generate a report for shift logs with IDs 1, 2, and 3.

This documentation should help you understand the `closeShift`, `getTankShiftLogs` and `shiftPrint` methods and the logic behind the shift print report.
