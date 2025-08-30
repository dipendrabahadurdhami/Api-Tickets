# Api-Tickets

Api-Tickets is a PHP-based bus ticket booking system that allows users to search for buses, book seats, view booking history, and receive notifications. The project uses MySQL for data storage and PHPMailer for sending email confirmations.

## Features

- **Bus Search & Booking:** Users can search for available buses, select routes, and book seats.
- **Booking Summary:** After booking, users receive a summary and confirmation email.
- **Notifications:** Users are notified of booking status and updates, with options to mark as read or delete notifications.
- **User Management:** Includes login, registration, password reset, and profile management.
- **Admin & Staff Panel:** Staff and admin users can manage buses, seats, and view user lists.
- **Responsive UI:** Designed for usability across devices.

## Project Structure

```
bookings/                # Booking data and scripts
booking/                 # Booking workflow (search, select, summary)
bus/                     # Bus management (add, list, manage seats)
includes/                # Shared components (header, footer)
notifications/           # Notification handling
reset_password/          # Password reset functionality
users/                   # User authentication and profile
vendor/                  # Composer dependencies (PHPMailer, etc.)
index.php                # Main entry point
config.php               # Database configuration
README.md                # Project documentation
```

## Installation

1. **Clone the repository:**
   ```sh
   git clone https://github.com/yourusername/api-tickets.git
   ```
2. **Install dependencies:**
   ```sh
   composer install
   ```
3. **Configure the database:**
   - Edit `config.php` with your MySQL credentials.

4. **Set up your web server:**
   - Point your server root to the project directory.

## Usage

- **User:** Register/login, search for buses, book seats, view history and notifications.
- **Staff/Admin:** Login, manage buses and seats, view users.

## Email Notifications

Booking confirmations are sent using PHPMailer. Configure SMTP settings in the relevant booking scripts.

## License

Distributed under the [LGPL 2.1](https://www.gnu.org/licenses/old-licenses/lgpl-2.1.html) license. See [vendor/phpmailer/phpmailer/LICENSE](vendor/phpmailer/phpmailer/LICENSE) for details.

## Contributing

Pull requests are welcome. For major changes, please open an issue first.

## Support

For inquiries, contact: +977-9809461534

---

**Note:** This project requires PHP >= 5.5.0 and a MySQL
