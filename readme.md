 # djmblog

 ---

### Extracted from the [danmckeown.info](https://danmckeown.info) web site for 2022

## quickstart

---

- go to /post/connect.php and update the database connection info to a currently available mySQL database
  - (optional) instead of editing connect.php, create `/post/db.local.php` (git-ignored) that sets `$servername`, `$username`, `$password` and `$dbname`; it overrides the defaults
    - this file is in the web root, so only use it if your server executes `.php` files (it should), and never commit it
- upload the dminfo.sql file to your mySQL admin (e.g. `PHPmyAdmin`) to set up the schema
- start the server using PHP e.g. run MAMP or type `PHP -S localhost:8989` in the terminal
- when visting the site in a browser, for the posting tool, go to the home page

---

![D logo](./static/img/pacificpelican_DJM_blog_logo_no_text_black_and_white_1aae8f91-7370-4b39-a681-5431d06fdd8c.webp)

---

## requirements
- PHP
- mySQL
