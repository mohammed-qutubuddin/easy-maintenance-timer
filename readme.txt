=== Easy Maintenance Timer ===
Contributors: abdulnasir1995
Tags: maintenance mode, coming soon, under construction, elementor, woocommerce
Requires at least: 6.0
Tested up to: 7.0
Requires PHP: 7.4
Stable tag: 1.03
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Fast, zero-bloat maintenance mode plugin with a countdown timer, 503 SEO headers, pure CSS animations, lead capture, and role-based access bypass.

== Description ==

Are you tired of "Coming Soon" and "Maintenance Mode" plugins that load megabytes of heavy page builders, aggressive upsells, and unnecessary scripts just to show a simple timer? 

**Easy Maintenance Timer** is the hyper-lightweight, developer-friendly alternative. Built strictly for performance, this plugin lets you instantly lock down your site with a single click while sending a dynamic **503 Service Unavailable header** to protect your SEO rankings with Google.

Configure your custom maintenance message, upload a custom logo (or use our transparent SVG fallback), capture visitor emails, add pure CSS background animations, and set an expiration date—all from a native, fast WordPress settings page. No bloat, no premium add-ons, just a clean, functional maintenance screen.

### 🚀 Core Features (Zero Bloat)
* **One-Click Maintenance Toggle:** Instantly put your site in maintenance or under-construction mode.
* **100% Builder & WooCommerce Compatible:** Seamlessly works alongside Elementor, Divi, Beaver Builder, WPBakery, and WooCommerce without breaking background processes or visual editors.
* **Auto-Live & Admin Notification:** Set an end date/time, and the plugin will automatically turn off maintenance mode when the countdown hits zero and instantly send the Admin an email notification that the site is live.
* **Lead Capture Form:** Collect visitor email addresses while your site is down and easily export the list as a CSV file.
* **Pure CSS Animated Backgrounds:** Add modern, premium touches like a "Slow Zoom" (Ken Burns effect) or a dynamic "Color Gradient" background without loading a single line of external JavaScript.
* **Dynamic 503 SEO Headers:** Calculates the exact downtime remaining and tells Google exactly when to check back, protecting your search rankings.
* **Granular Access Control:** Allow specific user roles (Authors, Editors, Shop Managers) to bypass the maintenance screen and view the live site.
* **Secret Client Bypass Link:** Generate a secure URL token to share with clients or stakeholders so they can view the under-construction site without needing a WordPress login!
* **Social & Contact Integrations:** Easily add lightweight, transparent SVG icons for Email, WhatsApp, Facebook, X (Twitter), LinkedIn, and Instagram.
* **Custom CSS & Scripts:** Easily inject Google Analytics, Facebook Pixels, or custom CSS directly into the maintenance page.
* **In-Dashboard Support:** Submit bug reports, feature requests, or support tickets directly from the plugin settings page.
* **Instant Cache Flushing:** Automatically purges popular caching plugins (WP Rocket, LiteSpeed, W3 Total Cache) when toggled, so changes are immediate.

### 👨‍💻 Built for Performance & Developers
The plugin strictly separates assets, loading **zero CSS or JS files** on your live site when the maintenance mode is turned off. Even when maintenance mode is active, the frontend CSS is injected inline to prevent external HTTP requests. It is built strictly following WordPress Plugin Check (PCP) guidelines for maximum security and speed, and includes full translation readiness.

== Installation ==

1. Download the zip file of the plugin.
2. In your WordPress admin panel, go to **Plugins > Add New > Upload Plugin**.
3. Upload the zip file and click **Install Now**, then **Activate**.
4. Go to **Settings > Maintenance Mode** to configure your message, upload your logo, set your countdown timer, and flip the switch to ON.

== Frequently Asked Questions ==

= Is it completely free? =
Yes. There are no premium upsells, no pro versions, and no hidden charges. It is 100% free and open-source.

= Will this break my Elementor or WooCommerce setup? =
Absolutely not! Easy Maintenance Timer is designed to be highly compatible with all major page builders (Elementor, Divi, Beaver Builder) and eCommerce platforms (WooCommerce). 

= Does this hurt my SEO? =
No. Easy Maintenance Timer specifically fires a `503 Service Temporarily Unavailable` HTTP header. It also dynamically calculates the exact number of seconds remaining on your timer and sets a `Retry-After` header. This explicitly tells search engines like Google that your site is down for updates and exactly when to check back, protecting your current rankings.

= Who can bypass the maintenance screen? =
Site Administrators bypass the screen automatically. You can also manually select other roles (like Editors or Subscribers) to bypass it. Finally, you can use the "Secret Client Bypass URL" to let anyone with the link view the site without creating an account.

= What happens when the countdown timer hits zero? =
Unlike other plugins, Easy Maintenance Timer features an auto-off function. When the countdown expires, the plugin automatically deactivates maintenance mode, restores your live site to the public, and sends the Site Admin a "Website is Live" notification email.

= Will changes show up immediately if I use caching? =
Yes. The plugin is hooked into major caching engines (including WP Rocket, LiteSpeed Cache, W3 Total Cache, and standard object caches). When you toggle the maintenance mode on or off, it forces a cache flush so your visitors see the correct state immediately.

== Screenshots ==
1. General Settings: Easily toggle maintenance mode, configure the live countdown timer, and customize your logo and background animations.
2. Access Control (Bypass): Generate a secret client bypass URL and select specific user roles that can view the live site.
3. Lead Capture & CSV Export: View and export the list of visitors who subscribed to be notified when your site goes live.
4. Front end: The clean, responsive visitor-facing maintenance page featuring the active countdown timer, subscriber form, and social contact buttons.

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