=== Multilingual File Items ===
Contributors: giannissifakis
Tags: files, downloads, shortcode, polylang, multilingual, divi
Requires at least: 6.0
Requires PHP: 7.4
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Create file-based content items and display them in a responsive two-column grid using a shortcode, with Polylang language support.

== Description ==

Multilingual File Items adds a dedicated File Items content type to WordPress. Each item includes:

* A title
* A square featured image
* A file selected from or uploaded to the WordPress Media Library
* A manual order value
* A Polylang language and translation relationship when Polylang is active

Use `[file_items]` in the WordPress editor, a Divi Text module, or a Divi Code module. The grid displays two items per row on desktop and one item per row on mobile. Clicking an item opens its file in a new browser tab.

== Installation ==

1. Upload the plugin ZIP through Plugins > Add New > Upload Plugin.
2. Activate Multilingual File Items.
3. Go to File Items > Add New.
4. Add a title, square item image, and file.
5. Publish the item.
6. Add `[file_items]` to any page or Divi module.

== Polylang ==

When Polylang is active, File Items are registered as translatable. Assign each item a language and connect its translations through the standard Polylang language panel. The shortcode automatically shows items in the current page language.

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

= Can I upload PDF, DOC, images, ZIP, and other file types? =

The media selector supports all file types permitted by your WordPress installation. WordPress and your hosting configuration determine which MIME types can be uploaded.

= What happens when an item has no featured image? =

If the selected file is an image, the plugin uses that image. For other file types, it displays a file-type fallback with the file extension.

= How do I change the order? =

Edit an item and use the Order field in the Page Attributes panel. Items with lower numbers appear first.

== Changelog ==

= 1.0.0 =
* Initial release.
