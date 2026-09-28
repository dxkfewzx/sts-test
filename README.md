# SYSTEMS IT - HTML Demo

This is a static HTML demonstration of the original PHP-based SYSTEMS IT inventory management system.

## Features

- Dashboard showing summary of equipment
- Inventory list management (add, edit, delete items)
- Serial Number management (add, edit, delete SNs, change status)
- Repair history management (add, edit status, delete repairs)
- Reports page showing statistics
- All data is stored in the browser's localStorage (no server or database required)
- Responsive design with mobile sidebar toggle
- Modal dialogs for all CRUD operations
- SweetAlert2 for notifications

## Files

- `index.html` - Dashboard
- `inventory_list.html` - Manage inventory items
- `details.html` - Manage SNs for a specific item
- `repair_details.html` - Manage repair history
- `report.html` - Statistics and reports
- `fetch_sn.php` - Original PHP endpoint (not used in this demo, kept for reference)

## How to Use

1. Open any HTML file in a modern web browser (Chrome, Firefox, Safari, Edge).
2. The data will persist in the browser's localStorage until cleared.
3. To reset the data, clear the browser cache or localStorage for this site.

## Notes

- This is a demonstration only. For production use, a server-side backend with database is required.
- The original PHP files (except db.php) have been converted to HTML with equivalent functionality using JavaScript and localStorage.
- The styling and behavior closely match the original PHP version.

## Storage Keys

- `inventoryItems`: Array of inventory items
- `snRecords`: Array of serial number records
- `repairRecords`: Array of repair records

Each record has an `id` field (timestamp-based) for uniqueness in this demo.

---
*Converted from PHP to HTML static demo*