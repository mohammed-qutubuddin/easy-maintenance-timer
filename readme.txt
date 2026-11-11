=== Easy Maintenance Mode, Coming Soon Page & Under Construction with Countdown Timer ===
Contributors: abdulnasir1995
Tags: maintenance mode, coming soon, under construction, countdown timer, maintenance
Requires at least: 6.0
Tested up to: 7.1
Requires PHP: 7.4
Stable tag: 1.04
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Ultra-fast Maintenance Mode, Coming Soon & Under Construction with countdown timer, 503 SEO headers, client secret bypass, and visitor lead capture.

== Description ==

Are you tired of "Coming Soon" and "Maintenance Mode" plugins that load megabytes of heavy page builders, annoying upsells, and nag paywalls just to show a countdown timer? 

**Easy Maintenance Timer** is the ultra-lightweight, developer-friendly alternative. Built strictly for speed, modern aesthetics, and SEO protection, this plugin lets you instantly put your website into **Maintenance Mode**, **Coming Soon Page**, or **Under Construction Mode** with a single click.

It safeguards your search engine rankings by returning dynamic **503 Service Temporarily Unavailable** headers with an intelligent `Retry-After` calculation, telling Googlebot, Bing, and search crawlers exactly when to return.

Capture visitor emails with the built-in lead subscription form, generate a secret client bypass link to showcase progress to clients without user accounts, enjoy pure CSS animations with zero external bloat, and preview changes in real time across Desktop, Tablet, and Mobile sandboxes.

### 🌟 Why Choose Easy Maintenance Timer vs Competitors?

| Feature | Easy Maintenance Timer | SeedProd / CMP / Competitors |
| :--- | :--- | :--- |
| **Pricing** | **100% FREE & Open Source** | \$49 – \$199/yr for basic features |
| **Page Speed & Bloat** | **Ultra-lightweight (<40KB Frontend)** | Heavy 10MB+ page builders |
| **Secret Client Bypass Link** | **Included Free** | Locked behind Pro paywall |
| **Lead Capture & CSV Export** | **Included Free** | Locked behind Pro paywall |
| **Webhook / Zapier Integration**| **Included Free** | Locked behind Pro paywall |
| **503 SEO Header + Retry-After**| **Included Free** | Often incorrect 200 OK or Pro |
| **Live In-Dashboard Sandbox** | **Included Free (Desktop/Tablet/Mobile)**| Basic or Paid |
| **Admin Bar Quick-Toggle** | **Included Free** | Missing or Paid |
| **Pure CSS Animated Backgrounds**| **Included Free** | Heavy JS or Pro |
| **No Annoying Upsells / Ads** | **Guaranteed Clean** | Aggressive upgrade nags |

---

### 🚀 Powerful Features (Zero Bloat, 100% Free)

* **One-Click Maintenance Mode:** Instantly toggle maintenance mode or under construction mode with a modern, intuitive switch in your WordPress dashboard or directly from the WordPress Admin Bar.
* **Responsive Countdown Timer:** Display a real-time, synchronized countdown timer that automatically adapts to all screen sizes and device pixel ratios.
* **Dynamic 503 SEO Header Protection:** Unlike basic plugins that return a 200 OK status (which risks Google indexing unfinished pages), this plugin returns a proper 503 HTTP status header with dynamic `Retry-After` headers matching your remaining timer.
* **Secret Client Bypass Link:** Working with clients or external stakeholders? Generate a secure tokenized URL so they can review the live website without ever needing a WordPress user account.
* **Lead Capture & 1-Click CSV Export:** Collect visitor email addresses with an integrated subscription form. View subscribers in your dashboard and export the entire list to CSV with one click.
* **Webhook & CRM Integration:** Push captured subscriber emails in real-time via JSON POST webhooks directly to Zapier, Make, Mailchimp, HubSpot, or any custom API endpoint.
* **Automated Subscriber Welcome Emails:** Send an instant auto-responder email to new subscribers confirming their subscription, with custom placeholders `{site_name}`, `{site_url}`, and `{email}`.
* **Live In-Dashboard Preview Sandbox:** Test your maintenance page across interactive Desktop, Tablet, and Mobile viewport frames directly from your settings panel before taking it live.
* **Admin Bar Status Indicator & Quick Toggle:** A discreet status badge in the top WordPress Admin Bar (`🔴 Maintenance ON` / `🟢 Site Live`) lets administrators check status and toggle maintenance mode from any page in 1 click.
* **Search Engine Bot Whitelist:** Optionally allow legitimate crawlers (Googlebot, Bingbot, Baiduspider, Yandex, DuckDuckBot) to crawl your site uninterrupted during maintenance.
* **Granular Role-Based Access Control:** Select which logged-in roles (Editors, Authors, Shop Managers) can bypass the maintenance screen and view the live website.
* **IP Whitelisting:** Whitelist specific static or office IP addresses (IPv4 and IPv6) to allow direct frontend access.
* **Scheduled Maintenance Window:** Set automated start and end timestamps so maintenance mode turns ON and OFF automatically without manual intervention.
* **Smart Auto-Live & Admin Alerts:** When the countdown hits zero, maintenance mode automatically turns off and immediately sends an alert email to the site administrator.
* **Passcode Bypass Form:** Allow authorized visitors to unlock the live website directly on the frontend by typing a private passcode.
* **Pure CSS Animated Backgrounds:** Choose from Ken Burns Slow Zoom or dynamic CSS Gradient Shift backgrounds without loading heavy external JavaScript files.
* **Open Graph & Twitter Social Cards:** Control the thumbnail image, title, and description displayed when your website link is shared on WhatsApp, Facebook, X (Twitter), LinkedIn, iMessage, and Slack.
* **Instant Cache Flushing:** Hooks automatically into WP Rocket, LiteSpeed Cache, W3 Total Cache, and WordPress Object Cache to purge caches whenever the mode changes.
* **Clean Data Uninstall:** Total control over your database. Enable the clean uninstall option to remove all stored options and subscriber tables upon removal.

---

### 🌟 100% Theme, Builder & eCommerce Compatible

Easy Maintenance Timer works seamlessly with your existing WordPress setup:
* **Page Builders:** Elementor, Divi, Beaver Builder, WPBakery, Oxygen, Bricks, and Gutenberg Block Editor.
* **eCommerce:** WooCommerce, Easy Digital Downloads (EDD), and SureCart. Background webhooks and payment processing are completely protected.
* **Caching Engines:** WP Rocket, LiteSpeed Cache, W3 Total Cache, WP Super Cache, and Redis/Memcached.

---

== Installation ==

**Option 1: From WordPress Dashboard (Easiest)**
1. In your WordPress admin, navigate to **Plugins > Add New**.
2. Search for **Easy Maintenance Mode** or **Easy Maintenance Timer**.
3. Click **Install Now**, then click **Activate**.
4. Go to **Settings > Easy Maintenance** (or use the Admin Bar indicator) to configure your message, countdown timer, and turn the mode **ON**.

**Option 2: Manual Upload**
1. Download the `easy-maintenance-timer.zip` archive.
2. In your WordPress admin, go to **Plugins > Add New > Upload Plugin**.
3. Select the zip file and click **Install Now**, then **Activate**.

---

== Frequently Asked Questions ==

= Is Easy Maintenance Timer completely free? =
Yes! 100% free with zero locked features, zero pro paywalls, and zero nag screens. Every feature listed is available out of the box.

= Will this hurt my Google rankings or SEO? =
No, it is specifically designed to protect your SEO. The plugin returns a proper `503 Service Temporarily Unavailable` HTTP header with an accurate `Retry-After` response header. This instructs search engines that your site is undergoing planned maintenance and tells them when to crawl again. You can also toggle the Search Engine Bot Whitelist to keep Googlebot crawling uninterrupted.

= How do I share the under-construction website with my client? =
Go to the **Access Control** card in the settings and copy your unique **Secret Client Bypass URL**. Send that link to your client; clicking it sets a secure authorization cookie allowing them to browse the live site without logging in.

= How do I export captured email subscribers? =
In the plugin settings, scroll down to the **Subscribers** card and click the **Export to CSV** button to download your email subscriber list.

= Can I push subscribers to Zapier or Mailchimp? =
Yes! Enable the Webhook integration in the Subscribers card and paste your Zapier / Make / Webhook URL. Whenever a visitor submits their email, the data is pushed immediately as a JSON POST request.

= What happens when the countdown timer ends? =
When the countdown expires, the plugin automatically disables maintenance mode, restores the live website to the public, and sends an automated email notification to the site administrator.

= Does this plugin slow down my live website? =
Not at all. When maintenance mode is disabled, the plugin loads **zero CSS and zero JavaScript files** on your frontend. When active, all styles are rendered inline for instantaneous rendering (<40KB total weight).

---

== Screenshots ==

1. **Dashboard & General Settings:** Simple 1-click maintenance toggle, live countdown timer, and branding controls.
2. **Interactive Live Preview Sandbox:** Real-time preview with Desktop, Tablet, and Mobile responsive view switchers.
3. **Granular Access Control:** Secret client bypass URL generator, user role permissions, and IP whitelisting.
4. **Subscriber Management & CSV Export:** Real-time subscriber table, CSV exporter, and automated webhook integration.
5. **Visitor-Facing Maintenance Display:** Clean, responsive maintenance page featuring the active countdown timer, subscription form, and social links.

---

== Changelog ==

= 1.04 (11 November 2026) =
* **Feature:** Added WordPress Admin Bar quick status badge (`🔴 Maintenance ON` / `🟢 Site Live`) with 1-click toggle and preview actions.
* **Feature:** Added In-Dashboard Live Preview Sandbox with interactive Desktop, Tablet, and Mobile responsive view switchers.
* **Feature:** Added Open Graph & Twitter Social Card Meta Tags for rich link preview banners when sharing on WhatsApp, Facebook, X, and LinkedIn.
* **Feature:** Added Webhook Integration (instantly push captured leads via asynchronous JSON POST payloads to Zapier, Make, Mailchimp, or custom CRMs).
* **Feature:** Added Subscriber Welcome / Confirmation Auto-Responder Email with customizable template tags ({site_name}, {site_url}, {email}).
* **Feature:** Added Search Engine Bot Whitelist toggle to allow Googlebot, Bingbot, Baiduspider, Yandex, and DuckDuckBot to crawl without interruption.
* **Feature:** Added Mode Type Selection (Switch between SEO 503 Maintenance Mode and Indexable 200 Coming Soon Mode).
* **Feature:** Added Frontend Layout Presets (Modern Centered Card, Left-Split Screen, and Minimalist Focus layouts).
* **Feature:** Added Scheduled Maintenance Window (automated start date/time and end date/time execution).
* **Feature:** Added Google Fonts integration, Video Backgrounds (YouTube & MP4), Progress Completion Bar, and Passcode / Password Bypass.
* **Feature:** Added Page Exclusions whitelist, Social Share buttons, Landing Page Contact Us form, and Conversion Analytics.
* **Feature:** Added non-intrusive 5-star review request notice in admin settings to support community development.
* **Feature:** Added optional "Powered by Easy Maintenance Timer" viral growth attribution badge.
* **Feature:** Added complete internationalization (`languages/easy-maintenance-timer.pot`) for WordPress Polyglots community translations.
* **Security:** Cryptographic 32-hex client bypass secret token with constant-time HMAC cookie verification (`hash_equals`).
* **Security:** Hardened TCP `REMOTE_ADDR` IP spoofing mitigation, CSV Formula Injection defense (CWE-1236), and Webhook SSRF validation.
* **Improvement:** Refactored entire codebase to clean PSR-4-compatible Object-Oriented Architecture with autoloader, controller classes, and template views.
* **Improvement:** 100% strict compliance with official WordPress Plugin Check (PCP) guidelines (0 Errors, 0 Warnings).
* **Fix:** Resolved global timezone discrepancies by converting dates to Unix timestamp in milliseconds for 100% synchronized countdown timers across all client timezones.
* **Fix:** Fixed settings save lockout bug where disabled form controls were omitted from POST submission when maintenance mode was inactive.

= 1.03 (15 June 2026) =
* **Feature:** Added Pure CSS Animated Backgrounds (Ken Burns Slow Zoom & Smooth Gradients) for a premium UI without JS bloat.
* **Feature:** Added Lead Capture Form (Collect visitor emails with 1-click CSV export capability).
* **Feature:** Added Auto-Live Email Notification (Instantly notifies the site admin when the timer ends and the site goes public).
* **Feature:** Added Custom CSS & Custom Tracking Scripts fields.
* **Feature:** Added In-Dashboard Support Ticket submission system.
* **Feature:** Added a modern, sticky "Save Changes" bar with glassmorphism effect in the admin panel.
* **Improvement:** Achieved 100% strict compliance with the official WordPress Plugin Check (PCP) guidelines.
* **Improvement:** Removed `frontend.css` completely; all frontend styles are now rendered securely inline for "True Zero-Bloat" performance.
* **Improvement:** Converted all admin JavaScript to strict ES5 to ensure flawless backward compatibility with older WordPress versions and browsers.
* **Improvement:** Optimized database queries and eliminated unnecessary `SHOW TABLES` calls from AJAX requests.
* **Improvement:** Integrated WordPress object cache (`wp_cache_set`/`wp_cache_delete`) to store subscriber lists securely without redundant DB queries.
* **Fix:** Resolved a frontend glitch where the countdown timer would flash blank for 1 second on initial page load.
* **Fix:** Handled `wp_date()` dependencies strictly to ensure backwards compatibility all the way down to WordPress 5.2 and PHP 5.6.
* **Fix:** Hardened security by fully preparing all SQL queries using `$wpdb->prepare` and enforcing output escaping across all admin and frontend views.

= 1.02 (05 June 2026) =
* **Feature:** Added proper 503 HTTP status headers to protect SEO rankings during downtime.
* **Feature:** Implemented Auto-Expiration logic; maintenance mode now automatically turns off when the timer hits zero.
* **Feature:** Added automatic cache flushing for WP Rocket, LiteSpeed, W3TC, and Object Cache.
* **Feature:** Added Granular Access Control—allow specific user roles to bypass the maintenance screen.
* **Feature:** Added Secret Client Bypass URL—allow non-logged-in clients to view the site via a secure token link.
* **Feature:** Added Social & Contact Links—lightweight SVG icons for WhatsApp, Email, Facebook, X, LinkedIn, and Instagram.
* **Feature:** Integrated a modern, precise Flatpickr date/time calendar in the admin dashboard.
* **Feature:** Added dynamic SEO "Retry-After" header that calculates exact downtime remaining for Google.
* **Feature:** Added "Clean Data on Uninstall" toggle to give users total control over database cleanup.
* **Feature:** Added standard translation text domain support for global localization.
* **Improvement:** Removed default background logo box in favor of a clean, native SVG timer icon to support dark/gradient themes perfectly.
* **Improvement:** Redesigned the admin settings page into a modern Card UI.
* **Improvement:** Refactored entire plugin architecture to strictly comply with WordPress Plugin Check (PCP) standards.
* **Improvement:** Expanded the deactivation feedback modal to capture detailed technical bug reports securely.
* **Fix:** Corrected WhatsApp URL prefixing to prevent `http://` formatting errors.
* **Fix:** Resolved UI styling conflicts between WordPress admin and custom datepicker.
* **Fix:** Aligned all settings input fields for a cleaner dashboard experience.

= 1.01 (03 June 2026) =
* **Improvement:** UI improved #1
* **Fix:** Default logo and animation added #1
* **WordPress:** Tested with WordPress version 7.0 #2
* **PHP:** Tested with PHP version 8.4 #2
* **Feature:** Feedback form added #3

= 1.0 =
* **Initial Release:** Maintenance ON/OFF toggle, custom message, logo, date/time picker, and countdown.
