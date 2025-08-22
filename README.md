# 🐷 PiggyTip.me

**Your own cozy corner on the web — one adorable page with all your important links!**

Share one link, get all the love — PayPal, Binance Pay, crypto. We track the buzz for you!💌🐷

[![Website](https://img.shields.io/badge/Website-piggylink.me-FF96AF?style=for-the-badge)](https://piggytip.me)
[![Demo](https://img.shields.io/badge/Live_Demo-piggylink.me-FF96AF?style=for-the-badge&logo=globe)](https://piggytip.me)
[![PHP](https://img.shields.io/badge/PHP-8.4-777BB4?style=for-the-badge&logo=php)](https://php.net)

![PiggyLink Platform Screenshot](https://piggylink.me/piggytip.jpg)

## Features
- Clean, modern dark UI (Tailwind CDN)
- Creator public page with links and crypto wallets
- Dashboard to manage links, wallets, and profile
- Simple privacyfriendly analytics (pageviews and clicks)
- Public comments with lightweight math captcha
- Register/Login with password hashing
- Config-first setup via config/app.php

## Tech stack
- PHP 8.1+ (works on 8.2/8.3)
- MySQL/MariaDB
- Tailwind (via CDN)

## Project layout
`
app/
  Controllers/         # Page, Auth, Dashboard, Api
  Lib/                 # Helpers + Validator
  Middleware/          # Auth gate
  Models/              # PDO models
  Views/               # PHP templates
config/app.php         # Single source of truth for app + DB config
public/                # Web root (assets + index.php)
  assets/
  img/
database/            # installer helper (optional)
README.md
`

## Requirements
- PHP 8.1+ with PDO MySQL extension
- MySQL/MariaDB 10+
- Web server (Nginx/Apache). Recommended: Nginx with web root set to public/.

## Quick start (local)
1. Clone the repo.
2. Create a database.
3. Configure config/app.php (DB host, name, user, pass; optional app.url).
4. Apply schema:
   - EITHER run the installer in a browser: database/schema.php
5. Start PHP + web server pointing the document root to public/.

## Configuration
Edit once and forget: config/app.php


## Nginx (if you are using nginx)
Set the site root to public/ and use clean URLs:
`

# Clean URLs → front controller
location / {
    try_files $uri $uri/ /index.php?$query_string;
}

# Static assets (cache)
location ~* \.(css|js|png|jpg|jpeg|gif|svg|webp|ico|woff2?|ttf|map)$ {
    try_files $uri =404;
    expires 7d;
    add_header Cache-Control "public, max-age=604800";
    access_log off;
}

# Favicon (physical file in /public)
location = /favicon.ico {
    try_files /favicon.ico =404;
    types { } default_type image/x-icon;
    expires 7d;
    access_log off;
}

# PHP handling
location ~ \.php$ {
    fastcgi_split_path_info ^(.+\.php)(/.+)$;
    include fastcgi_params;
    fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
    fastcgi_param DOCUMENT_ROOT $realpath_root;
    fastcgi_intercept_errors on;
    # Choose one:
    # fastcgi_pass unix:/run/php/php8.2-fpm.sock;
    fastcgi_pass 127.0.0.1:9000;
    fastcgi_index index.php;
}

# Security
location ~ /\. { deny all; }
`

## Captcha
- Public user page comments use a lightweight math captcha (/api/captcha).
- Register form also includes a math captcha validated server-side.

## Database changes / troubleshooting
- All expected columns are defined in public/database/schema.sql.
- If migrating from a different schema, ensure users has: username, email, password_hash, display_name, bio, avatar, slug, primary_link_id, is_active, created_at.

## Security
- CSRF protection on POST routes
- Passwords hashed with password_hash
- Minimal thirdparty dependencies

