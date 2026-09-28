# Tank Shift API Endpoints

## TankShift Object

```json
{
    "id": integer,
    "tank_id": integer,
    "product_id": integer,
    "opening_totalizer": integer,
    "closing_totalizer": integer,
    "manual_opening_totalizer": integer,
    "manual_closing_totalizer": integer,
    "user_id": integer,
    "is_modified": boolean,
    "created_at": string,
    "updated_at": string
}
```

## TankShiftLog Object

```json
{
    "id": integer,
    "tank_id": integer,
    "product_id": integer,
    "opening_totalizer": integer,
    "closing_totalizer": integer,
    "manual_opening_totalizer": integer,
    "manual_closing_totalizer": integer,
    "user_id": integer,
    "is_modified": boolean,
    "data": object,
    "created_at": string,
    "updated_at": string
}
```

## Check Shift

*   **Endpoint:** `/api/tanks/tank-shifts/check/{tankId}`
*   **Method:** GET
*   **Description:** Checks if a shift exists for a given tank ID.
*   **Parameters:**
    *   `tankId` (integer, required): The ID of the tank.
*   **Response:**
    ```json
    {
        "status": "exists" | "not_exists",
        "shift": {
            // TankShift object (if status is "exists")
        }
    }
    ```

## Update Shift

*   **Endpoint:** `/api/tanks/tank-shifts/update`
*   **Method:** POST
*   **Description:** Updates an existing tank shift.
*   **Parameters:**
    *   `id` (integer, required): The ID of the tank shift.
    *   `opening_totalizer` (integer, nullable): The opening totalizer value.
    *   `closing_totalizer` (integer, nullable): The closing totalizer value.
    *   `manual_opening_totalizer` (integer, nullable): The manual opening totalizer value.
    *   `manual_closing_totalizer` (integer, nullable): The manual closing totalizer value.
    *   `tank_id` (integer, nullable): The ID of the tank.
    *   `product_id` (integer, nullable): The ID of the product.
    *   `user_id` (integer, nullable): The ID of the user.
    *   `is_modified` (boolean, nullable): Indicates if the shift has been modified.
*   **Response:**
    ```json
    {
        "status": "success",
        "shift": {
            // Updated TankShift object
        }
    }
    ```

## Open Shift

*   **Endpoint:** `/api/tanks/tank-shifts/open`
*   **Method:** POST
*   **Description:** Opens a new tank shift.
*   **Parameters:**
    *   `tank_id` (integer, required): The ID of the tank.
    *   `product_id` (integer, required): The ID of the product.
    *   `user_id` (integer, required): The ID of the user.
    *   `is_modified` (boolean, nullable): Indicates if the shift has been modified.
*   **Response:**
    ```json
    {
        "status": "success",
        "shift": {
            // Created TankShift object
        }
    }
    ```

## Close Shift

*   **Endpoint:** `/api/tanks/tank-shifts/close/{id}`
*   **Method:** POST
*   **Description:** Closes an existing tank shift and creates a new one. The closed shift data is moved to the `tank_shift_logs` table.
*   **Parameters:**
    *   `id` (integer, required): The ID of the tank shift to close.
    *   `closing_totalizer` (integer, required): The closing totalizer value.
*   **Response:**
    ```json
    {
        "status": "success",
        "shift": {
            // Created TankShift object for the new shift
        }
    }

## Close All Shifts

*   **Endpoint:** `/api/tanks/tank-shifts/close-all`
*   **Method:** POST
*   **Description:** Closes all open tank shifts.
*   **Response:**
    ```json
    {
        "status": "success",
        "message": "All shifts closed successfully."
    }
    ```

## Get Tank Shift Logs

*   **Endpoint:** `/api/tanks/tank-shift-logs`
*   **Method:** GET
*   **Description:** Retrieves tank shift logs with optional filters.
*   **Parameters:**
    *   `tank_id` (integer, optional): The ID of the tank to filter by.
    *   `start_date` (date, optional): The start date to filter by (YYYY-MM-DD).
    *   `end_date` (date, optional): The end date to filter by (YYYY-MM-DD).
*   **Response:**
    ```json
    {
        "status": "success",
        "tank_shift_logs": [
            // Array of TankShiftLog objects
        ]
    }
