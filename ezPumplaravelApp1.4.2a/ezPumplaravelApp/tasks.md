### Milestones for System Enhancements

**Phase 1: Employee Table and Cashier Name Handling**
1. Modify the UI to fetch cashier names from the employee table as a selectable dropdown field.
2. Ensure the cashier field is editable only once after initial selection.

**Phase 2: V_ID Integration**
3. Add a new column `v_id` to the following tables:
   - `sale_data`
   - `saledata_send`
   - `customer_vehicle`
4. Ensure `v_id` is saved properly when data is written.
5. Make `v_id` custom writable only for unselected customers.

**Phase 3: Shift Handling and Alerts**
6. On **shift opening**:
   - If the alert is enabled, truncate the existing employee table.
   - Copy data from the shadow table into the employee table.
7. On **shift closing**:
   - Create an alert.
8. On **shift opening**:
   - Create an alert.
9. On **rate change**:
   - Create an alert.
   - Ensure rate change does not work if the value is 0.
10. On **credit customer sale**:
    - Create an alert.
    - Increment the value of the customer's limit used by the amount sold.
    - Before incrementing, check if the customer's limit is already exceeded.
    - Exclude credit limit checks for managers.
11. On **shift close**:
    - Save all entries of the current shift and `shift_n$1` into another table for backup purposes.

**Phase 4: Customer Limit Management**
12. Implement customer functionality to:
    - Track the limit used.
    - Prevent sales once the limit has been exceeded.

**Phase 5: Additional Enhancements**
13. Implement **Status Update API**.
14. Ensure decimal points are handled properly in values.
15. Make fields one-time editable:
    - Opening balance.
    - Employee.

**Phase 6: Homepage Redesign**
16. Redesign the homepage to include:
    - Custom vehicle number input for walk-in customers.
    - Date-only input fields where required.
    - Show only current shift data for users.
    - Show all data to admin as non-editable.

**Phase 7: Database Updates**
17. Update `PUMP_STATE` table:
    - Add `status` (BOOLEAN, NOT NULL, DEFAULT TRUE).
    - Add `shift_status` (TINYINT, NOT NULL, DEFAULT '1').
18. Add employee code from the shift table.

**Phase 8: Shift Timing Enhancements**
19. Add shift closing duration timer in settings.
20. Add shift start time in settings during `closeAllShift` method.

**Phase 9: Tank Management Features**
21. **Tank Tables**:
    - Tanks
    - Tank Reading (Dip Chart)
    - Tank Stock Entry
22. **Tank Pages**:
    - Associated product
    - List Tanks (stock) (parameters)
    - Add/Edit/Delete Tanks
    - Add Stock
    - Stock History
    - Option to see/edit dip chart readings/upload Excel file
    - Tank Alarms
23. Add two more fields:
    - Manual opening dip value of the tank.
    - Manual closing dip value of the tank.

**Phase 10: Login Enhancements**
24. On the login page:
    - Prompt users with an empty password to set their own password.

**Phase 11: Shift Table Enhancements**
25. Add pie chart for each product.
26. Add sale count.
27. Add payment method-wise sales.

**Phase 12: Testing and Validation**
28. Perform unit testing for each implemented feature.
29. Conduct integration testing for overall system performance and behavior.
30. Document all implemented changes and testing results.

**Future Enhancements**
- Discuss additional tasks after milestone completion.
- Optimize database queries for performance improvement.
- Introduce role-based access for critical actions.

---
Let me know once the above milestones are completed, and we can proceed further.

