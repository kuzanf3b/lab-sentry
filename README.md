# Lab Sentry

Lab Sentry is a web-based Laboratory Inventory Management System built with PHP and MySQL. It helps laboratory administrators manage equipment, track asset conditions, and maintain inventory records efficiently.

## Features

- **User Authentication**: Secure login and registration system for administrators.
- **Inventory Management**: Add, view, update, and manage laboratory assets.
- **Asset Tracking**: Track asset details including name, asset code, condition (Good, Damaged, Under Repair), and stock levels.
- **Dashboard**: Overview of laboratory inventory status and statistics.
- **Loan & Reports**: Dedicated modules for tracking item loans and generating inventory reports.

## Tech Stack

- **Backend**: PHP 8.3+
- **Database**: MySQL (PDO for secure database interactions)
- **Frontend**: HTML5, CSS (via Assets), and PHP Views
- **Architecture**: Simple MVC-like structure with separated configurations, modules, and views.

## Project Structure

```text
├── assets/             # CSS, JS, and image files
├── config/             # Database connection and app settings
├── db_lab_sentry.sql   # Database schema and initial setup
├── index.php           # Main entry point and routing
├── modules/            # Business logic and processing
└── views/              # UI templates and page fragments
```

## Installation

1. **Clone the repository**:
   ```bash
   git clone https://github.com/kuzanf3b/lab-sentry.git
   cd lab-sentry
   ```

2. **Database Setup**:
   - Create a new MySQL database named `db_lab_sentry`.
   - Import the `db_lab_sentry.sql` file into your database.

3. **Configuration**:
   - Update `config/koneksi.php` with your database credentials.
   - Alternatively, you can use environment variables: `DB_HOST`, `DB_PORT`, `DB_NAME`, `DB_USER`, `DB_PASS`.

4. **Run the Application**:
   - Host the project on a PHP-enabled web server (e.g., Apache, Nginx, or PHP's built-in server).
   - If using PHP's built-in server, run:
     ```bash
     php -S localhost:8000
     ```
   - Access the application in your browser at `http://localhost:8000`.

## Usage

- **Register**: Create a new admin account.
- **Login**: Access the dashboard.
- **Inventory**: Navigate to the Inventory section to add or update items.
- **Reports**: Generate summaries of current laboratory equipment.

## License

This project is open-source. Please check the repository for license details.
