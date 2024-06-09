### 1. Clone the Repository

```bash
git clone https://github.com/hirenkhatavara/crud-using-ajax.git
cd crud-using-ajax
```

### 2. Install Dependencies
```
composer install
```

### 3. Environment Configuration
# 1. Copy the .env.example file to create a new .env file:

```
cp .env.example .env

```
# 2. Generate an application key:

```
php artisan key:generate

```
# 3. Open the .env file and configure your database and other environment settings:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 4. Run Migrations and link storage folder
```
php artisan migrate
php artisan storage:link 

```
### 5. Run database seeder for the categories and hobbies records
```
php artisan db:seed
```

### 6. Serve the Application
```
php artisan serve
```


