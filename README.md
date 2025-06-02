# Restaurant Management System 

This is a web-based **Restaurant Management System** developed using **HTML, CSS, JavaScript, PHP, and MySQL**. It is designed to simplify and digitize the ordering, billing, and kitchen management 
process for restaurants, cafes, and food courts. The system includes an interactive frontend for customers to place orders, as well as administrative dashboards for the kitchen and restaurant owner to manage
and monitor the flow of orders in real time.

##  Features & Functionalities

### 1. **Item Display & Customer Ordering**
- The home page (`index.php`) displays a menu of available food items with images, names, and prices.
- Users can click to add items to a cart.
- The cart dynamically updates quantities, shows item-wise costs, and displays the total bill.
- Users can increase or decrease item quantities, or remove items from the cart entirely.
- Once satisfied, the customer can click **"Place Order"**, and the order is sent to the kitchen.

### 2. **Cart Management**
- Implemented using JavaScript to allow real-time cart updates.
- Stored in `localStorage` to preserve cart state during navigation.
- Prevents duplicate entries by updating the quantity of already-added items.
- Displays a summary of items with total quantity and cost.

### 3. **Order Placement Backend (place_order.php)**
- Receives cart data and stores it in a MySQL database table (`orders` or similar).
- Generates a unique order ID and associates it with items and quantities.
- Sends real-time updates to the kitchen system.

### 4. **Kitchen Dashboard (`kitchen.php`)**
- Displays incoming orders with item names, quantities, and timestamps.
- Allows kitchen staff to update the status of each order (e.g., "Preparing", "Ready", "Completed").
- Automatically refreshes or updates when new orders arrive.

### 5. **Owner Dashboard (`owner.php`)**
- Shows a complete list of placed and completed orders.
- Includes functionality to **generate bills**, showing a breakdown of items, quantities, individual costs, taxes, and total.
- Helps in tracking sales, order volume, and identifying popular items.
- May include date-based filtering or order searching for reports.

### 6. **Database Integration**
- Uses MySQL to store item details (name, price, image), orders, and statuses.
- Designed with normalization principles to avoid redundancy.
- SQL schema includes tables like `items`, `orders`, `order_items`, and optionally `users`.

### 7. **UI/UX Enhancements**
- Responsive design suitable for desktops and tablets.
- Clean card-based layout for item display.
- Separate views for customers, kitchen, and owners for better modularity.

## Technologies Used

- **Frontend**: HTML, CSS, JavaScript
- **Backend**: PHP
- **Database**: MySQL
- **Other**: XAMPP (Apache + MySQL server)

## Folder Structure

- `index.php` – Main customer interface
- `place_order.php` – Backend script for handling orders
- `kitchen.php` – Kitchen staff interface
- `owner.php` – Owner's dashboard for billing and reports
- `assets/` – Item images, CSS files, or JS files
- `restaurant.sql` – MySQL database schema

## Future Enhancements

- Add login/authentication for kitchen and owner panels
- Add inventory management
- Generate sales reports and analytics
- Enable online ordering and payment gateway integration

