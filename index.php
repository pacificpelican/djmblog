<?php
define('DB_OPTIONAL', true);
include './post/connect.php';
include './post/postHeader.php';

$username = $_SESSION["poster"] ?? null;
$uu_id = $_SESSION["uu_id"] ?? null;
?> 

<!DOCTYPE html>
<html lang="en">

<head>
  <title>djmblog posts</title>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../static/post.css">

  <style>
    #intro { max-width: 46rem; margin-top: 1.5rem; line-height: 1.55; font-size: 1.05rem; }
    #intro h1 { margin: 0 0 .3em; display: flex; align-items: center; gap: .4em; }
    #intro img#brandMark { height: 1.6em; width: auto; }
    #intro .tagline { font-size: 1.25rem; margin-top: 0; }
    #intro h2 { margin-top: 1.8em; }
    #intro ol, #intro ul { padding-left: 1.4em; }
    #intro li { margin-bottom: .4em; }
    #intro code { background: #eee; padding: 0 .3em; border-radius: 2pt; }
    #intro .cta { display: inline-block; margin: .5em 0; padding: .5em 1em; border: 2px solid #000; color: #000; text-decoration: none; font-weight: bold; }
    #intro .cta:hover { background: #000; color: #fff; }
    #intro .credit { margin-top: 2em; color: gray; font-size: .95rem; }
    #intro .dbnote { margin-top: 1em; font-size: .95rem; }
    aside#logo img#logo { width: calc(25vw + 10pt); border-width: 1em; }
  </style>
</head>

<body>
  <div id="container">
  

  <h2><a href="./posts">Posts</a></h2>

  <section id="intro">
    <h1><img id="brandMark" src="./static/img/djmblog-logo.svg" alt="" /> djmblog</h1>
    <p class="tagline">A tiny, hand-built blog and commenting app for plain PHP and MySQL.</p>

    <p>djmblog lets people register, log in, write posts, and comment on each other's posts. No framework, no build step, no dependencies to install: just a handful of PHP files you upload and run. It's the lightweight kind of web app that runs happily on ordinary shared hosting.</p>

    <a class="cta" href="https://github.com/pacificpelican/djmblog">Get djmblog on GitHub</a>

    <h2>What you get</h2>
    <ul>
      <li>User accounts with hashed passwords, login and logout</li>
      <li>Posts and comments, each with its own permanent link</li>
      <li>A "latest posts" page and a personal account page to manage your own content</li>
      <li>Safe by default: prepared SQL statements, CSRF protection, sanitized post HTML and login rate limiting</li>
      <li>Shared-hosting friendly: sessions fall back to a writable folder if the host's default isn't available</li>
    </ul>

    <h2>Up and running in minutes</h2>
    <ol>
      <li>Put the files on any server with PHP and MySQL.</li>
      <li>Enter your database details in <code>post/connect.php</code> (or in a private <code>post/db.local.php</code>).</li>
      <li>Import <code>dminfo.sql</code> with phpMyAdmin or similar to create the tables.</li>
      <li>Open the site and register. For local testing, <code>php -S localhost:8989</code> is all it takes.</li>
    </ol>

    <p class="credit">Created by <a href="https://altaredwood.work">Daniel McKeown</a></p>

    <?php if (!$db_ok) { ?>
    <p class="dbnote">The database isn't connected yet. <a href="https://github.com/pacificpelican/djmblog#setting-the-database-info">How to hook up the database</a> (see the readme).</p>
    <?php } ?>
  </section>

  <aside id="logo">
    <a href="https://github.com/pacificpelican/djmblog"><img id="logo" src="./static/img/pacificpelican_DJM_blog_logo_no_text_black_and_white_1aae8f91-7370-4b39-a681-5431d06fdd8c.webp" alt="logo" /></a>
  </aside>
    
  </div>
</body>

</html>
