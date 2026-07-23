=== Multilingual File Items ===
Contributors: giannissifakis
Tags: files, links, downloads, shortcode, polylang, multilingual, divi
Requires at least: 6.0
Requires PHP: 7.4
Stable tag: 1.1.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Create file or custom-link items and display them in a responsive two-column grid using a shortcode, with Polylang language support.

== Description ==

Multilingual File Items adds a dedicated File Items content type to WordPress. Each item includes:

* A title
* A square featured image
* Either a file selected from the WordPress Media Library or a custom HTTP/HTTPS link
* A manual order value
* A Polylang language and translation relationship when Polylang is active

Use `[file_items]` in the WordPress editor, a Divi Text module, or a Divi Code module. The grid displays two items per row on desktop and one item per row on mobile. Clicking an item opens its selected destination in a new browser tab.

== Installation ==

1. Upload the plugin ZIP through Plugins > Add New > Upload Plugin.
2. Activate Multilingual File Items.
3. Go to File Items > Add New.
4. Add a title and square item image.
5. Select Uploaded file or Custom link in the Item Destination panel.
6. Add the file or URL and publish the item.
7. Add `[file_items]` to any page or Divi module.

== Polylang ==

When Polylang is active, File Items are registered as translatable. Assign each item a language and connect its translations through the standard Polylang language panel. The shortcode automatically shows items in the current page language.

Each translation may use its own uploaded file or custom URL.

== Shortcode ==

Basic:

`[file_items]`

Optional examples:

`[file_items limit="8"]`

`[file_items language="de"]`

`[file_items language="all"]`

`[file_items orderby="title" order="ASC"]`

`[file_items class="my-custom-grid"]`

Supported attributes:

* `limit`: Number of items. Default: `-1` for all.
* `language`: Polylang language slug. Leave blank for current language, or use `all`.
* `orderby`: `menu_order`, `title`, `date`, `modified`, or `rand`.
* `order`: `ASC` or `DESC`.
* `class`: Additional CSS classes on the grid wrapper.

== Frequently Asked Questions ==

= Does Polylang need to be installed? =

No. The plugin also works on single-language websites. Polylang is only required for language-specific items and linked translations.

= Can I use an external link instead of uploading a file? =

Yes. Select Custom link in the Item Destination panel and enter the complete HTTP or HTTPS URL.

= Can I upload PDF, DOC, images, ZIP, and other file types? =

The media selector supports all file types permitted by your WordPress installation. WordPress and your hosting configuration determine which MIME types can be uploaded.

= What happens when an item has no featured image? =

If the uploaded file or direct custom URL is an image, the plugin can use that image. Otherwise, it displays a file-type or link fallback.

= How do I change the order? =

Edit an item and use the Order field in the Page Attributes panel. Items with lower numbers appear first.

== Changelog ==

= 1.1.0 =
* Added Uploaded file and Custom link destination choices.
* Added support for internal and external HTTP/HTTPS URLs.
* Added link-specific fallback and admin display.
* Preserved compatibility with existing file items.

= 1.0.0 =
* Initial release.
