 <img src="./static/img/djmblog-logo.svg" alt="djmblog logo" width="120" />

 # djmblog

 ---

### Extracted from the [danmckeown.info](https://danmckeown.info) web site for 2022

## quickstart

---

- connect djmblog to your MySQL database (see [setting the database info](#setting-the-database-info) below)
- upload the dminfo.sql file to your mySQL admin (e.g. `PHPmyAdmin`) to set up the schema
- start the server using PHP e.g. run MAMP or type `PHP -S localhost:8989` in the terminal
- when visting the site in a browser, for the posting tool, go to the home page

---

## setting the database info

djmblog needs four pieces of information to reach your MySQL database: the server name, a database username, that user's password, and the database name. They are plain PHP variables, set once near the top of a file; no special setup, just put them at the top level of the file (not inside a function).

### option 1: edit `post/connect.php`

Find these four lines near the middle of the file and replace the values with your own:

```php
$servername = "localhost";
$username = "djmblog";
$password = "1111111";
$dbname = "djmblog";
```

e.g. on shared hosting such as cPanel, it might look like this (cPanel usually prefixes names with your account name):

```php
$servername = "localhost";
$username = "myaccount_blogger";
$password = "a-long-random-password";
$dbname = "myaccount_djmblog";
```

### option 2 (recommended for a live site): create `post/db.local.php`

Create a new file named `db.local.php` in the same folder as `connect.php`. It starts with `<?php` and sets the same variables. If it exists, it overrides the values in `connect.php`, so you can update `connect.php` later without losing your settings. It's listed in `.gitignore`, so it won't be committed.

```php
<?php
$servername = "localhost";
$username = "myaccount_blogger";
$password = "a-long-random-password";
$dbname = "myaccount_djmblog";
```

You only need to include the settings you want to change. For example, if only the password differs from the defaults, this is a complete file:

```php
<?php
$password = "a-long-random-password";
```

### closing sign-ups

To stop new people from registering (existing users can still log in), add this line to `db.local.php` (or to `connect.php`):

```php
$open_registration = false;
```

Remove the line, or set it to `true`, to open registration again.

### troubleshooting

- A blank page or a "Database connection failed" message usually means one of the four values is wrong, or the database user hasn't been given access to that database in your hosting control panel.
- The front page still loads without a database and shows a link back to this section. Your host's `error_log` file contains the exact reason.

---

![D logo](./static/img/pacificpelican_DJM_blog_logo_no_text_black_and_white_1aae8f91-7370-4b39-a681-5431d06fdd8c.webp)

---

## requirements
- PHP
- mySQL

## exporting your content

On the account page, **PDF export** opens a printable copy of your posts and
comments. **WP export** downloads a WordPress WXR XML file for import using
WordPress's Tools > Import > WordPress importer. It includes your posts and
your comments, not other users' content. Comments on posts outside your export
are attached to labeled draft placeholder posts so they can be imported without
including the original authors' content. Post titles are generated from their
djmblog IDs because djmblog posts do not have titles. Exported dates are UTC.
Images and other linked files remain referenced by URL; their files are not
included in the export.
