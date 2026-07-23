# Multilingual File Items

A lightweight WordPress plugin for creating file or link items and displaying them in a responsive, language-aware shortcode grid.

Each item includes a title, a square image, and one selected destination:

- A file uploaded to or selected from the WordPress Media Library
- A manually entered internal or external URL

Items appear two per row on desktop and one per row on mobile. Clicking an item opens its destination in a new browser tab.

## Features

- Dedicated **File Items** custom post type
- Choice between **Uploaded file** and **Custom link** for every item
- Support for PDFs, Word documents, images, spreadsheets, presentations, ZIP files, and other WordPress-supported media
- Support for webpages, Google Drive links, forms, videos, cloud files, and other HTTP/HTTPS URLs
- Square featured image for every item
- Automatic thumbnail fallback when the uploaded file or custom URL points directly to an image
- File-extension or link-icon fallback when no square image is selected
- Responsive two-column grid
- One-column mobile layout
- Destinations open in a new tab
- Manual item ordering
- Divi-compatible shortcode
- Polylang custom post type integration
- Automatic current-language filtering
- Translation-ready plugin interface
- No external libraries or services

## Requirements

- WordPress 6.0 or newer
- PHP 7.4 or newer
- Polylang or Polylang Pro for multilingual functionality

Polylang is optional. Without it, the plugin works as a standard single-language item grid.

## Installation

1. Download the plugin ZIP file.
2. In WordPress, open **Plugins → Add New → Upload Plugin**.
3. Upload the ZIP file and select **Install Now**.
4. Activate **Multilingual File Items**.
5. A new **File Items** section will appear in the WordPress dashboard.

Existing items created with version 1.0.0 remain compatible and continue using their uploaded files.

## Creating an Item

1. Open **File Items → Add New**.
2. Enter the item title.
3. Use **Square Item Image** to upload or select the square image.
4. In **Item Destination**, select one of the following:
   - **Uploaded file**: select or upload a file through the WordPress Media Library.
   - **Custom link**: enter a complete URL beginning with `http://` or `https://`.
5. Optionally set an **Order** number in **Page Attributes**. Lower values appear first.
6. Assign the appropriate Polylang language when applicable.
7. Publish the item.

The plugin retains both the uploaded-file selection and custom URL when you switch destination types. Only the currently selected destination type is displayed on the frontend.

## Basic Shortcode

Add the following shortcode to a WordPress page, post, Divi Text module, or Divi Code module:

```text
[file_items]
```

By default, it displays all published items in the current language, ordered by the manual **Order** value and then alphabetically by title.

## Shortcode Attributes

### Limit the number of items

```text
[file_items limit="8"]
```

Use `-1` to display all items. This is the default.

### Display a specific language

```text
[file_items language="de"]
```

Replace `de` with the required Polylang language slug.

### Display all languages

```text
[file_items language="all"]
```

This bypasses the automatic current-language filter.

### Change the ordering

```text
[file_items orderby="title" order="ASC"]
```

Supported `orderby` values:

- `menu_order`
- `title`
- `date`
- `modified`
- `rand`

Supported `order` values:

- `ASC`
- `DESC`

### Add a custom CSS class

```text
[file_items class="project-publications"]
```

The class is added to the main grid wrapper and can be used for page-specific styling.

### Combined example

```text
[file_items limit="10" language="es" orderby="title" order="ASC" class="spanish-resources"]
```

## Polylang Setup

The plugin registers **File Items** as a translatable custom post type automatically.

For each language:

1. Create the first File Item.
2. Assign its language using the Polylang language panel.
3. Use the Polylang **+** translation control to create the equivalent item in another language.
4. Add the translated title and select the appropriate uploaded file or custom link.
5. Add the corresponding square image.
6. Publish each translation.

The standard shortcode:

```text
[file_items]
```

automatically displays only items assigned to the current page language.

If the language controls do not appear, open **Languages → Settings → Custom post types and Taxonomies** and confirm that **File Items** is enabled.

## Divi Usage

The shortcode can be placed inside:

- A Divi **Text Module**
- A Divi **Code Module**
- The standard WordPress Shortcode block

The plugin supplies its own responsive grid and does not require a Divi row with two columns.

## Image Fallback Behaviour

The frontend selects the item image in this order:

1. The File Item's square featured image
2. The uploaded file itself, when it is an image
3. The custom URL itself, when it points directly to a supported image extension
4. A generic document or link icon with the detected extension

For consistent presentation, using a dedicated square featured image is recommended.

## Destination Support

### Uploaded file

The media selector displays file types allowed by the WordPress installation. WordPress, installed security plugins, and server configuration may restrict some extensions.

The plugin stores the WordPress attachment ID and uses the current attachment URL on the frontend.

### Custom link

Custom links must use `http://` or `https://`. They may point to:

- A normal webpage
- Google Drive, Dropbox, OneDrive, or another cloud service
- A form or questionnaire
- A video or media resource
- A file hosted outside WordPress
- Another page on the same website

## Styling

The grid uses the following main CSS classes:

```css
.mfi-grid {}
.mfi-item {}
.mfi-item__link {}
.mfi-item__image {}
.mfi-item__img {}
.mfi-item__title {}
.mfi-item__fallback {}
.mfi-item__extension {}
```

Example customization:

```css
.mfi-item__link {
    border-radius: 0;
}

.mfi-item__title {
    font-size: 18px;
}
```

Add custom overrides to the Divi Theme Options custom CSS field, the child theme stylesheet, or the WordPress Additional CSS panel.

## Security and Performance

- File attachment IDs are validated before saving.
- Custom links are sanitized and restricted to HTTP and HTTPS protocols.
- Administrative changes use WordPress nonces and capability checks.
- Frontend URLs and output are escaped.
- Links use `noopener noreferrer` and open in a new tab.
- Frontend CSS is loaded only when the shortcode returns items.
- The frontend query disables unnecessary pagination and term-cache work.

## Folder Structure

```text
multilingual-file-items/
├── assets/
│   ├── css/
│   │   ├── admin.css
│   │   └── frontend.css
│   └── js/
│       └── admin.js
├── multilingual-file-items.php
├── README.md
└── readme.txt
```

## Changelog

### 1.1.0

- Added a destination type selector to every item
- Added support for manually entered internal and external URLs
- Added link-specific admin display and frontend fallback icon
- Preserved compatibility with existing uploaded-file items

### 1.0.0

- Initial release
- Added File Items custom post type
- Added WordPress media file selector
- Added responsive shortcode grid
- Added Polylang integration
- Added manual ordering
- Added featured image and file-type fallbacks


## Author

**Giannis Sifakis**  
[LinkedIn Profile](https://www.linkedin.com/in/giannis-sifakis-03b30b240/)
