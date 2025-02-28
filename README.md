# E-Commerce Shop Setup Guide

This guide will walk you through setting up the e-commerce application from scratch, including all prerequisites and configuration steps.

## Prerequisites

### 1. Install PHP

**Windows:**
1. Download PHP from [windows.php.net](https://windows.php.net/download/)
2. Extract to a folder (e.g., `C:\php`)
3. Add PHP to your PATH environment variable
4. Rename `php.ini-development` to `php.ini`
5. Edit `php.ini` and uncomment these extensions:
   ```
   extension=fileinfo
   extension=pdo_mysql
   extension=mysqli
   extension=openssl
   ```

**macOS:**
    ```
    brew install php
    ```

**Linux (Ubuntu/Debian):**
    ```
    sudo apt update
    sudo apt install php php-cli php-fpm php-json php-common php-mysql php-zip php-gd php-mbstring php-curl php-xml php-pear php-bcmath
    ```

### 2. Install Composer

**Windows:**
1. Download and run the installer from [getcomposer.org](https://getcomposer.org/download/)

**macOS/Linux:**
    ```
    php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
    php composer-setup.php
    php -r "unlink('composer-setup.php');"
    sudo mv composer.phar /usr/local/bin/composer
    ```

### 3. Install MySQL

**Windows:**
1. Download and install MySQL from [dev.mysql.com](https://dev.mysql.com/downloads/installer/)

**macOS:**
    ```
    brew install mysql
    brew services start mysql
    ```
**Linux (Ubuntu/Debian):**
    ```
    sudo apt update
    sudo apt install mysql-server
    sudo systemctl start mysql
    sudo systemctl enable mysql
    ```

### 4. Install Node.js and npm

Download and install from [nodejs.org](https://nodejs.org/)

## Project Setup

### 1. Clone the Repository
    ```
    git clone https://github.com/yourusername/ecommerce-shop.git
    cd ecommerce-shop
    ```
    
### 2. Install PHP Dependencies
    ```
    composer install
    ```
    
### 3. Install JavaScript Dependencies
    ```
    npm install
    ```
    
### 4. Environment Configuration
    ```
    cp .env.example .env
    ```
    
    Edit the `.env` file to set up your database connection:

    ```
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=ecommerce_shop
    DB_USERNAME=root
    DB_PASSWORD=your_password
    ```

### 5. Create the Database
    ```
    mysql -u root -p
    ```
    
In the MySQL prompt:
    ```
    CREATE DATABASE ecommerce_shop;
    EXIT;
    ```


### 6. Generate Application Key
    ```
    php artisan key:generate
    ```

### 7. Run Migrations and Seed the Database
    ```
    php artisan migrate --seed
    ```
    
### 8. Create Storage Link
    ```
    php artisan storage:link
    ```

### 9. Build Assets
    ```
    npm run dev
    ```

### 10. Start the Development Server
    ```
    php artisan serve
    ```

The application will be available at http://127.0.0.1:8000

## Default Admin Account

After seeding the database, you can log in with:
- Email: admin@example.com
- Password: password

## Directory Structure

- `app/` - Contains the core code of the application
- `database/` - Contains database migrations and seeders
- `public/` - The document root for the application
- `resources/` - Contains views, raw assets, and language files
- `routes/` - Contains all route definitions
- `storage/` - Contains uploaded files, logs, and compiled files

## Key Features

1. **Product Management**
   - Create, edit, and delete products
   - Manage product categories
   - Upload product images

2. **User Management**
   - User registration and authentication
   - Admin and customer roles

3. **Shopping Cart**
   - Add products to cart
   - Update quantities
   - Remove items

4. **Order Processing**
   - Checkout process
   - Order history

## Troubleshooting

### Image Upload Issues

If you encounter issues with image uploads:

1. Check that the `storage:link` command has been run
2. Ensure the `storage` directory is writable:
   ```bash
   chmod -R 775 storage
   ```

3. Verify your image paths in the views:
   - For images stored in public: `asset($product->image)`
   - For images stored in storage: `asset('storage/' . $product->image)`

### Database Connection Issues

If you can't connect to the database:

1. Check your `.env` file for correct credentials
2. Ensure MySQL service is running
3. Try connecting with a MySQL client to verify credentials

## Customization

### Changing the Theme

The application uses Tailwind CSS. To customize the theme:

1. Edit `tailwind.config.js`
2. Run `npm run dev` to rebuild assets

### Adding Payment Gateways

The application is prepared for payment gateway integration. To add a payment provider:

1. Install the provider's SDK
2. Create a service class in `app/Services`
3. Update the checkout controller to use your payment service

## Deployment

For production deployment:

1. Set up a production server with PHP, MySQL, and Nginx/Apache
2. Configure your web server to point to the `public` directory
3. Set environment variables for production
4. Run `npm run build` to compile assets for production
5. Set appropriate file permissions

## Contributing

1. Fork the repository
2. Create a feature branch
3. Submit a pull request

## License

This project is licensed under the MIT License.










