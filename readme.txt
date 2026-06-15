=== Easy Maintenance Timer ===
Contributors: abdulnasir1995
Tags: Tags: maintenance mode, coming soon, under construction, elementor, woocommerce
Requires at least: 6.0
Tested up to: 7.0
Requires PHP: 7.4
Stable tag: 1.03
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Zero-bloat maintenance mode with a countdown timer, 503 SEO headers, CSS animations, lead capture, and Elementor/WooCommerce compatibility.

== Description ==

Are you tired of "Coming Soon" and "Maintenance Mode" plugins that load megabytes of heavy page builders, aggressive upsells, and unnecessary scripts just to show a simple timer? 

**Easy Maintenance Timer** is the hyper-lightweight, developer-friendly alternative. Built strictly for performance and SEO optimization, this plugin lets you instantly lock down your site with a single click. It protects your search rankings with Google by sending a dynamic **503 Service Temporarily Unavailable header**, ensuring you don't lose your hard-earned SEO traffic while you update your website.

Configure your custom maintenance message, upload your brand logo (or use our transparent SVG fallback), capture visitor emails, add pure CSS background animations, and set an expiration date—all from a native, lightning-fast WordPress settings page. No bloat, no premium add-ons, just a clean, highly functional maintenance screen that actually converts.

### 🌟 100% Theme, Builder & eCommerce Compatible
We built Easy Maintenance Timer to seamlessly integrate with your existing tech stack. It works flawlessly out-of-the-box with:
* **Page Builders:** Elementor, Divi, Beaver Builder, WPBakery, Oxygen, and the native Gutenberg block editor.
* **eCommerce:** WooCommerce, Easy Digital Downloads (EDD), and SureCart. Background order processing and webhooks remain entirely uninterrupted while the frontend is hidden.
* **Caching Engines:** WP Rocket, LiteSpeed Cache, W3 Total Cache, and server-level object caching.

### 🚀 Detailed Core Features (Zero Bloat)

* **One-Click Maintenance Toggle:** Instantly put your site in maintenance or under-construction mode with a simple, modern UI switch.
* **Smart Auto-Live & Admin Alerts:** Set your countdown end date and time. When the timer hits zero, the plugin automatically turns off maintenance mode, making your site public again, and instantly sends an email notification to the Site Admin.
* **Lead Capture Form & CSV Export:** Don't lose potential customers while your site is under construction! Collect visitor email addresses with a built-in subscription form. Easily manage and export your entire list as a CSV file with one click.
* **Premium Pure CSS Animated Backgrounds:** Impress visitors with modern, premium visual touches like a "Slow Zoom" (Ken Burns effect) or a dynamic "Color Gradient" background. These are built entirely with CSS, meaning zero external JavaScript bloat to slow down your page.
* **Dynamic 503 SEO Headers:** Unlike cheap plugins that return 200 OK statuses (which ruins your SEO), this plugin calculates the exact downtime remaining on your timer and dynamically sets a `Retry-After` header. This tells Google exactly when to crawl your site again.
* **Granular Role-Based Access Control:** Need your editorial team or shop managers to keep working? Easily select specific user roles (Authors, Editors, Shop Managers) that can bypass the maintenance screen and view the live site while logged in.
* **Secret Client Bypass Link:** Working for a client? Generate a secure, tokenized URL to share with stakeholders. They can bypass the maintenance screen and view the under-construction site without ever needing a WordPress login!
* **Social Media & Contact Integrations:** Grow your following even when offline. Easily add lightweight, transparent SVG icons linked to your Email, WhatsApp, Facebook, X (Twitter), LinkedIn, and Instagram.
* **Custom CSS & Tracking Scripts:** Want to track traffic or run ads? Easily inject Google Analytics, Meta (Facebook) Pixels, TikTok pixels, or custom CSS directly into the maintenance page from the dashboard.
* **Instant Cache Flushing:** Automatic cache purging ensures that the moment you toggle maintenance mode on or off, your visitors see the correct state immediately without seeing a cached version of your site.
* **Clean Data Uninstall:** Keep your database clean. Use the built-in toggle to ensure all plugin data is wiped upon uninstallation, leaving zero orphaned rows behind.
* **In-Dashboard Support:** Submit bug reports, feature requests, or direct support tickets to our team right from the plugin settings page.

### 👨‍💻 Built for Ultimate Performance
Easy Maintenance Timer strictly separates assets. It loads **zero CSS or JS files** on your live site when the maintenance mode is turned off. When active, frontend CSS is injected inline to prevent external HTTP requests. Built strictly following official WordPress Plugin Check (PCP) guidelines for maximum security, speed, and translation readiness.

== Installation ==

**Option 1: Install via WordPress Dashboard (Recommended)**
1. Log into your WordPress admin panel.
2. Navigate to **Plugins > Add New**.
3. In the search bar, type **Easy Maintenance Timer**.
4. Locate the plugin (Author: abdulnasir1995) and click **Install Now**.
5. Once installed, click **Activate**.
6. Navigate to the new **Settings > Maintenance Mode** menu to configure your countdown, upload a logo, and toggle the mode **ON**.

**Option 2: Manual ZIP Installation**
1. Download the `easy-maintenance-timer.zip` file from the WordPress repository.
2. In your WordPress admin panel, go to **Plugins > Add New > Upload Plugin**.
3. Choose the downloaded `.zip` file and click **Install Now**.
4. Click **Activate**.
5. Navigate to **Settings > Maintenance Mode**, enter your desired message and date, configure your lead capture, and save your changes.

== Frequently Asked Questions ==

= Is it completely free? =
Yes! There are no premium upsells, no "Pro" versions hidden behind a paywall, and no hidden charges. Every feature listed is 100% free and open-source.

= Will this break my Elementor or WooCommerce setup? =
Absolutely not. Easy Maintenance Timer is meticulously designed for high compatibility with all major page builders (Elementor, Divi, Beaver Builder) and eCommerce platforms (WooCommerce). It hides the frontend presentation while ensuring your backend and background processes run smoothly.

= Does this hurt my SEO? =
No, it actually protects it. Easy Maintenance Timer specifically fires a proper `503 Service Temporarily Unavailable` HTTP header. Because you set a countdown timer, it dynamically calculates the exact number of seconds remaining and sets a `Retry-After` header. This explicitly tells search engines like Google that your site is down for planned updates and exactly when to check back, securing your current search rankings.

= Who can bypass the maintenance screen? =
By default, Site Administrators bypass the screen automatically. In the settings, you can manually select other roles (like Editors, Shop Managers, or Subscribers) to bypass it. You can also use the **Secret Client Bypass URL** to let anyone view the site using a special link, without needing a WordPress account.

= What happens when the countdown timer hits zero? =
Unlike older plugins that require you to log back in, Easy Maintenance Timer features an intelligent auto-off function. When the countdown expires, the plugin automatically deactivates maintenance mode, restores your live site to the public, and immediately sends the Site Admin a "Website is Live" notification email.

= How do I export the captured email leads? =
If you enable the Lead Capture Form, visitors can subscribe to be notified when you launch. Simply go to **Settings > Maintenance Mode**, scroll to the Lead Capture section, and click the **Export CSV** button to download your entire subscriber list instantly.

= Can I track visitors while the site is under construction? =
Yes! There is a dedicated "Custom Scripts" text box in the settings. You can paste your Google Analytics tracking code, Meta Pixel, or any other JavaScript tracking snippet directly into this box to monitor traffic during your downtime.

= Does the animated background slow down my site? =
Not at all. The premium animations (like the Ken Burns zoom and the color gradient) are built using 100% pure CSS. There are zero bulky JavaScript libraries loaded, ensuring the maintenance page renders instantly on desktop and mobile devices.

= Will changes show up immediately if I use caching? =
Yes. The plugin hooks directly into major caching engines (WP Rocket, LiteSpeed Cache, W3 Total Cache, and standard object caches). When you toggle the maintenance mode on or off, it forces a cache flush so your visitors see the correct state immediately.

== Screenshots ==
1. **General Settings:** Easily toggle maintenance mode, configure the live countdown timer, and customize your logo and background animations with a modern UI.
2. **Access Control (Bypass):** Generate a secret client bypass URL and select the specific user roles that are allowed to view the live site.
3. **Lead Capture & CSV Export:** Easily view and export the list of visitors who subscribed to be notified when your site goes live.
4. **Front End Display:** The clean, responsive, visitor-facing maintenance page featuring the active countdown timer, subscriber form, pure CSS background, and social contact buttons.

== Changelog ==

= 1.03 (11 June 2026) =
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