# 🐷 PiggyTip.me

**Your own cozy corner on the web — one adorable page with all your important links!**

Share one link, get all the love — PayPal, Binance Pay, crypto. We track the buzz for you!💌🐷

[![Website](https://img.shields.io/badge/Website-piggytip.me-FF96AF?style=for-the-badge)](https://piggytip.me)
[![Demo](https://img.shields.io/badge/Live_Demo-piggytip.me-FF96AF?style=for-the-badge&logo=globe)](https://piggytip.me)
[![PHP](https://img.shields.io/badge/PHP-8.1+-777BB4?style=for-the-badge&logo=php)](https://php.net)

![PiggyTip Platform Screenshot](https://piggylink.me/piggytip.jpg)

## ✨ Features
- 🎨 **Beautiful dark UI** with Tailwind CSS
- 🔗 **One-page profiles** with links and crypto wallets
- 📊 **Privacy-friendly analytics** (pageviews and clicks)
- 💬 **Public comments** with lightweight math captcha
- 🔐 **Secure authentication** with password hashing
- ⚙️ **Config-first setup** via `config/app.php`
- 🌐 **SEO-ready** with auto-generated sitemap

## 🛠️ Tech Stack
- **Backend**: PHP 8.1+ (works on 8.2/8.3/8.4)
- **Database**: MySQL/MariaDB
- **Frontend**: Tailwind CSS (via CDN)
- **Architecture**: Simple MVC with no frameworks

## 📁 Project Structure
```
app/
  Controllers/         # Page, Auth, Dashboard, API
  Lib/                 # Helpers + Validator
  Middleware/          # Auth middleware
  Models/              # PDO models
  Views/               # PHP templates
config/app.php         # Single source of truth for config
assets/                # JS, uploads, sponsors
img/                   # Logo, icons, images
database/              # SQL schema files
index.php              # Front controller
```

## 🚀 Quick Start

### Requirements
- PHP 8.1+ with PDO MySQL extension
- MySQL/MariaDB 10+
- Web server (Nginx/Apache)

### Installation
1. **Clone the repository**
   ```bash
   git clone https://github.com/sanjiproject/piggytip.git
   cd piggytip
   ```

2. **Import the database schema**
   ```bash
   mysql -u piggytip -p piggytip < database/schema.sql
   ```

## ⚙️ Nginx Configuration

```
    # Clean URLs → front controller
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    # Sitemap (dynamic)
    location = /sitemap.xml {
        try_files $uri /index.php?route=/sitemap.xml;
        access_log off;
    }

    # Static assets (cache)
    location ~* \.(css|js|png|jpg|jpeg|gif|svg|webp|ico|woff2?|ttf|map)$ {
        try_files $uri =404;
        expires 7d;
        add_header Cache-Control "public, max-age=604800";
        access_log off;
    }

    # Favicon
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
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        fastcgi_param DOCUMENT_ROOT $document_root;
        fastcgi_intercept_errors on;
        fastcgi_pass 127.0.0.1:9000;
        fastcgi_index index.php;
    }

    # Security
    location ~ /\. { deny all; }

```


## 🛡️ Security Features

- **CSRF protection** on all POST routes
- **Password hashing** with PHP's `password_hash()`
- **Input validation** and sanitization
- **Math captcha** for public forms
- **Minimal dependencies** (reduced attack surface)

## 📈 SEO & Analytics

- **Auto-generated sitemap** at `/sitemap.xml`
- **Privacy-friendly analytics** (no external tracking)
- **Open Graph meta tags** for social sharing
- **Schema.org structured data** for rich snippets

Try it out: **[piggytip.me](https://piggytip.me)**

---

Made with ❤️ by [SanjiProject](https://github.com/sanjiproject)