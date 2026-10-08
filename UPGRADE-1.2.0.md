# Multilingual File Items — upgrade to 1.2.0

## What changed

- Admin-only lifetime click counters for files and custom links.
- A Clicks column under **File Items → All File Items**.
- A Download / Link Clicks statistics box when editing an item.
- The public shortcode layout remains unchanged. Links now pass through a WordPress redirect to record the click.

## Installation using WordPress (full ZIP)

1. Create a backup of your current plugin folder and WordPress database.
2. Find your active plugin folder under `wp-content/plugins/`.
3. Use the full ZIP whose **internal root directory matches your currently installed folder**. This release uses `multilingual-file-items-main/`, matching the supplied GitHub archive. If you installed the plugin under `multilingual-file-items/`, use the standard-folder ZIP instead.
4. Go to **Plugins → Add New → Upload Plugin**, upload the selected ZIP, and choose **Replace current with uploaded** if WordPress prompts you.
5. Verify the plugin displays version 1.2.0 and remains active.
6. Clear any WordPress/Divi/site/CDN/page caches.
7. Open a page with `[file_items]`, click a published item, and inspect its new click total in WordPress Admin.

## Installation using Plesk / SFTP (patch ZIP)

1. Back up the existing plugin directory.
2. Extract the patch ZIP.
3. Copy the patched files into your existing active plugin folder (`wp-content/plugins/<your-current-folder>/`), keeping their relative paths and overwriting the old files.
4. Do not change the plugin folder name. You do **not** need to deactivate/reactivate or run a database migration.
5. Clear caches and perform a public click test.

## Counter semantics

- Each click on an item rendered by `[file_items]` increments the total for that WordPress item, then opens the file or link as before.
- The counter is **per WordPress item**. Each Polylang translation has its own count.
- Clicking links inside the WordPress admin does not increment public counts.
- The total is cumulative across destination changes to the same item.
- A counted click is **not** a verified completed download; direct requests to Media Library URLs are not tracked.
- Previously made clicks cannot be recovered, and automated requests that follow public links may also be counted.
