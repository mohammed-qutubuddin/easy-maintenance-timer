=== Easy Maintenance Timer ===
Contributors: abdulnasir1995
Tags: maintenance mode, coming soon, under construction, countdown timer, lightweight
Requires at least: 5.2
Tested up to: 7.0
Requires PHP: 8.4
Stable tag: 1.02
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Fast, zero-bloat plugin for maintenance or under-construction mode with a countdown timer, 503 SEO headers, and role-based access bypass.

== Description ==

Are you tired of "Coming Soon" and "Maintenance Mode" plugins that load megabytes of heavy page builders, aggressive upsells, and unnecessary scripts just to show a simple timer? 

**Easy Maintenance Timer** is the hyper-lightweight, developer-friendly alternative. Built strictly for performance, this plugin lets you instantly lock down your site with a single click while sending a dynamic **503 Service Unavailable header** to protect your SEO rankings with Google.

Configure your custom maintenance message, upload a custom logo (or use our transparent SVG fallback), add your social media links, and set an expiration date—all from a native, fast WordPress settings page. No bloat, no premium add-ons, just a clean, functional maintenance screen.

### 🚀 Core Features (Zero Bloat)
* **One-Click Maintenance Toggle:** Instantly put your site in maintenance or under-construction mode.
* **Auto-Expiration Logic:** Set an end date/time, and the plugin will automatically turn off maintenance mode when the countdown hits zero.
* **Dynamic 503 SEO Headers:** Calculates the exact downtime remaining and tells Google exactly when to check back, protecting your search rankings.
* **Granular Access Control:** Allow specific user roles (Authors, Editors, Shop Managers) to bypass the maintenance screen and view the live site.
* **Secret Client Bypass Link:** Generate a secure URL token to share with clients or stakeholders so they can view the under-construction site without needing a WordPress login!
* **Native Datepicker:** Features a fast, reliable date and time picker integrated directly into the settings dashboard with an easy Apply button.
* **Social & Contact Integrations:** Easily add lightweight, transparent SVG icons for Email, WhatsApp, Facebook, X (Twitter), LinkedIn, and Instagram to your maintenance page without loading heavy font libraries.
* **Instant Cache Flushing:** Automatically purges popular caching plugins (WP Rocket, LiteSpeed, W3 Total Cache) when toggled, so changes are immediate.
* **Zero-Bloat Database Cleanup:** Gives you the option to completely wipe all plugin data upon uninstallation, leaving your database perfectly clean.

### 👨‍💻 Built for Performance & Developers
The plugin strictly separates assets, loading zero CSS or JS on your live site when the maintenance mode is turned off. It is built strictly following WordPress Plugin Check (PCP) guidelines for maximum security and speed, and includes full translation readiness.

== Installation ==

1. Download the zip file of the plugin.
2. In your WordPress admin panel, go to **Plugins > Add New > Upload Plugin**.
3. Upload the zip file and click **Install Now**, then **Activate**.
4. Go to **Settings > Maintenance Mode** to configure your message, upload your logo, set your countdown timer, and flip the switch to ON.

== Frequently Asked Questions ==

= Is it completely free? =
Yes. There are no premium upsells, no pro versions, and no hidden charges. It is 100% free and open-source.

= Does this hurt my SEO? =
No. Easy Maintenance Timer specifically fires a `503 Service Temporarily Unavailable` HTTP header. It also dynamically calculates the exact number of seconds remaining on your timer and sets a `Retry-After` header. This explicitly tells search engines like Google that your site is down for updates and exactly when to check back, protecting your current rankings.

= Who can bypass the maintenance screen? =
Site Administrators bypass the screen automatically. You can also manually select other roles (like Editors or Subscribers) to bypass it. Finally, you can use the "Secret Client Bypass URL" to let anyone with the link view the site without creating an account.

= What happens when the countdown timer hits zero? =
Unlike other plugins, Easy Maintenance Timer features an auto-off function. When the countdown expires, the plugin automatically deactivates maintenance mode and restores your live site to the public.

= Will changes show up immediately if I use caching? =
Yes. The plugin is hooked into major caching engines (including WP Rocket, LiteSpeed Cache, W3 Total Cache, and standard object caches). When you toggle the maintenance mode on or off, it forces a cache flush so your visitors see the correct state immediately.

== Screenshots ==
1. General Settings: Easily toggle maintenance mode, configure the live countdown timer, and customize your logo and background.
2. Access Control (Bypass): Generate a secret client bypass URL and select specific user roles that can view the live site.
3. Social & Contact Links: Add your email, WhatsApp, and social media URLs to display clean, lightweight icons during downtime.
4. Front end: The clean, responsive visitor-facing maintenance page featuring the active countdown timer and social contact buttons.

== Changelog ==

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