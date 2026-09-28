# NextGen Digital

A NextGen Digital website with PHP and MySQL login, registration, and role-based team directories.

## Run with XAMPP

1. Copy the `LAB 7` folder into `C:\xampp\htdocs\`.
2. Start **Apache** and **MySQL** in the XAMPP Control Panel.
3. Open `http://localhost/phpmyadmin` and run `database/schema.sql` in the SQL tab. This creates the `company_portal` database.
4. Open `http://localhost/LAB%207/setup.php` once to create the users table and demo records.
5. Open `http://localhost/LAB%207/`.

Do not open `index.html` directly from File Explorer or with a `file://` URL. Browsers download PHP files when they are opened this way instead of running them. Start Apache and MySQL, then use the `http://localhost/...` address above. The homepage served by Apache is `index.php`, which displays the NextGen Digital landing page and connects its Login and Register links to the PHP account forms.

The default connection is in `config/database.php` and expects MySQL user `root` with an empty password, which is the default XAMPP setup.

## Demo accounts

- Admin: `admin@nextgendigital.test` / `admin123`
- Employee: `employee@nextgendigital.test` / `employee123`

Admin accounts see all employee details. Employee accounts see all admin details. New registrations are created as employee accounts.

Delete or protect `setup.php` after initializing a real deployment.