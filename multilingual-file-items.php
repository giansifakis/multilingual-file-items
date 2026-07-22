<?php
/**
 * Plugin Name: Multilingual File Items
 * Description: Create downloadable file items and display them in a responsive two-column shortcode grid with Polylang language support.
 * Version:     1.0.0
 * Author:      Giannis Sifakis
 * Author URI:  https://www.linkedin.com/in/giannis-sifakis-03b30b240/
 * Text Domain: multilingual-file-items
 * Domain Path: /languages
 * Requires at least: 6.0
 * Requires PHP: 7.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class MFI_Multilingual_File_Items {
	const VERSION      = '1.0.0';
	const POST_TYPE    = 'mfi_file_item';
	const META_FILE_ID = '_mfi_file_attachment_id';
	const NONCE_ACTION = 'mfi_save_file_item';
	const NONCE_NAME   = 'mfi_file_item_nonce';

	/**
	 * Bootstrap plugin hooks.
	 */
	public static function init() {
		add_action( 'init', array( __CLASS__, 'load_textdomain' ), 5 );
		add_action( 'init', array( __CLASS__, 'register_post_type' ) );
		add_action( 'after_setup_theme', array( __CLASS__, 'register_image_size' ) );

		add_filter( 'pll_get_post_types', array( __CLASS__, 'register_with_polylang' ), 10, 2 );

		add_action( 'add_meta_boxes_' . self::POST_TYPE, array( __CLASS__, 'add_meta_boxes' ) );
		add_action( 'save_post_' . self::POST_TYPE, array( __CLASS__, 'save_file_item' ) );
		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'enqueue_admin_assets' ) );

		add_filter( 'manage_' . self::POST_TYPE . '_posts_columns', array( __CLASS__, 'add_admin_columns' ) );
		add_action( 'manage_' . self::POST_TYPE . '_posts_custom_column', array( __CLASS__, 'render_admin_columns' ), 10, 2 );

		add_shortcode( 'file_items', array( __CLASS__, 'render_shortcode' ) );
	}

	/**
	 * Load translations.
	 */
	public static function load_textdomain() {
		load_plugin_textdomain(
			'multilingual-file-items',
			false,
			dirname( plugin_basename( __FILE__ ) ) . '/languages'
		);
	}

	/**
	 * Register the downloadable item post type.
	 */
	public static function register_post_type() {
		$labels = array(
			'name'                  => __( 'File Items', 'multilingual-file-items' ),
			'singular_name'         => __( 'File Item', 'multilingual-file-items' ),
			'menu_name'             => __( 'File Items', 'multilingual-file-items' ),
			'name_admin_bar'        => __( 'File Item', 'multilingual-file-items' ),
			'add_new'               => __( 'Add New', 'multilingual-file-items' ),
			'add_new_item'          => __( 'Add New File Item', 'multilingual-file-items' ),
			'new_item'              => __( 'New File Item', 'multilingual-file-items' ),
			'edit_item'             => __( 'Edit File Item', 'multilingual-file-items' ),
			'view_item'             => __( 'View File Item', 'multilingual-file-items' ),
			'all_items'             => __( 'All File Items', 'multilingual-file-items' ),
			'search_items'          => __( 'Search File Items', 'multilingual-file-items' ),
			'not_found'             => __( 'No file items found.', 'multilingual-file-items' ),
			'not_found_in_trash'    => __( 'No file items found in Trash.', 'multilingual-file-items' ),
			'featured_image'        => __( 'Square Item Image', 'multilingual-file-items' ),
			'set_featured_image'    => __( 'Set square item image', 'multilingual-file-items' ),
			'remove_featured_image' => __( 'Remove square item image', 'multilingual-file-items' ),
			'use_featured_image'    => __( 'Use as square item image', 'multilingual-file-items' ),
		);

		$args = array(
			'labels'              => $labels,
			'public'              => true,
			'publicly_queryable'  => false,
			'exclude_from_search' => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => false,
			'show_in_admin_bar'   => true,
			'show_in_rest'        => true,
			'menu_icon'           => 'dashicons-media-document',
			'capability_type'     => 'post',
			'map_meta_cap'        => true,
			'hierarchical'        => false,
			'supports'            => array( 'title', 'thumbnail', 'page-attributes' ),
			'has_archive'         => false,
			'rewrite'             => false,
			'query_var'           => false,
		);

		register_post_type( self::POST_TYPE, $args );
	}

	/**
	 * Register a cropped square image size for item thumbnails.
	 */
	public static function register_image_size() {
		add_image_size( 'mfi-square', 400, 400, true );
	}

	/**
	 * Ensure Polylang recognizes the custom post type as translatable.
	 *
	 * @param array $post_types Registered Polylang post types.
	 * @param bool  $is_settings Whether the filter is used on the Polylang settings screen.
	 * @return array
	 */
	public static function register_with_polylang( $post_types, $is_settings ) {
		$post_types[ self::POST_TYPE ] = self::POST_TYPE;
		return $post_types;
	}

	/**
	 * Register item settings meta boxes.
	 */
	public static function add_meta_boxes() {
		add_meta_box(
			'mfi-file-attachment',
			__( 'Item File', 'multilingual-file-items' ),
			array( __CLASS__, 'render_file_meta_box' ),
			self::POST_TYPE,
			'normal',
			'high'
		);

		add_meta_box(
			'mfi-shortcode-help',
			__( 'Shortcode & Language', 'multilingual-file-items' ),
			array( __CLASS__, 'render_help_meta_box' ),
			self::POST_TYPE,
			'side',
			'default'
		);
	}

	/**
	 * Output file selector UI.
	 *
	 * @param WP_Post $post Current post.
	 */
	public static function render_file_meta_box( $post ) {
		$file_id   = absint( get_post_meta( $post->ID, self::META_FILE_ID, true ) );
		$file_url  = $file_id ? wp_get_attachment_url( $file_id ) : '';
		$file_name = $file_id ? basename( get_attached_file( $file_id ) ?: $file_url ) : '';

		wp_nonce_field( self::NONCE_ACTION, self::NONCE_NAME );
		?>
		<div class="mfi-file-picker">
			<input type="hidden" id="mfi-file-attachment-id" name="mfi_file_attachment_id" value="<?php echo esc_attr( $file_id ); ?>">

			<div class="mfi-file-picker__selection"<?php echo $file_id ? '' : ' hidden'; ?>>
				<span class="dashicons dashicons-media-document" aria-hidden="true"></span>
				<div>
					<strong class="mfi-file-picker__name"><?php echo esc_html( $file_name ); ?></strong>
					<a class="mfi-file-picker__url" href="<?php echo esc_url( $file_url ); ?>" target="_blank" rel="noopener noreferrer">
						<?php esc_html_e( 'Open selected file', 'multilingual-file-items' ); ?>
					</a>
				</div>
			</div>

			<p class="description">
				<?php esc_html_e( 'Select any file available in the WordPress Media Library, including PDF, Word documents, images, spreadsheets, presentations, ZIP files, and other file types permitted by WordPress.', 'multilingual-file-items' ); ?>
			</p>

			<p>
				<button type="button" class="button button-primary mfi-select-file">
					<?php echo $file_id ? esc_html__( 'Replace File', 'multilingual-file-items' ) : esc_html__( 'Select or Upload File', 'multilingual-file-items' ); ?>
				</button>
				<button type="button" class="button-link-delete mfi-remove-file"<?php echo $file_id ? '' : ' hidden'; ?>>
					<?php esc_html_e( 'Remove file', 'multilingual-file-items' ); ?>
				</button>
			</p>
		</div>
		<?php
	}

	/**
	 * Output shortcode and Polylang usage guidance.
	 */
	public static function render_help_meta_box() {
		?>
		<p><?php esc_html_e( 'Place this shortcode in a page, Divi Text module, or Divi Code module:', 'multilingual-file-items' ); ?></p>
		<p><code>[file_items]</code></p>
		<p><?php esc_html_e( 'Use the Order field in Page Attributes to control the item order.', 'multilingual-file-items' ); ?></p>
		<?php if ( function_exists( 'pll_current_language' ) ) : ?>
			<p class="mfi-status mfi-status--success">
				<?php esc_html_e( 'Polylang detected. Assign a language and connect translations using the Polylang language panel.', 'multilingual-file-items' ); ?>
			</p>
		<?php else : ?>
			<p class="mfi-status">
				<?php esc_html_e( 'Polylang is not currently active. The plugin will still work as a single-language file grid.', 'multilingual-file-items' ); ?>
			</p>
		<?php endif; ?>
		<?php
	}

	/**
	 * Save selected attachment ID.
	 *
	 * @param int $post_id Current post ID.
	 */
	public static function save_file_item( $post_id ) {
		if ( ! isset( $_POST[ self::NONCE_NAME ] ) ) {
			return;
		}

		$nonce = sanitize_text_field( wp_unslash( $_POST[ self::NONCE_NAME ] ) );
		if ( ! wp_verify_nonce( $nonce, self::NONCE_ACTION ) ) {
			return;
		}

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		if ( wp_is_post_revision( $post_id ) ) {
			return;
		}

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		$file_id = isset( $_POST['mfi_file_attachment_id'] ) ? absint( $_POST['mfi_file_attachment_id'] ) : 0;

		if ( $file_id && 'attachment' === get_post_type( $file_id ) ) {
			update_post_meta( $post_id, self::META_FILE_ID, $file_id );
		} else {
			delete_post_meta( $post_id, self::META_FILE_ID );
		}
	}

	/**
	 * Load the media uploader only on this plugin's edit screens.
	 *
	 * @param string $hook_suffix Current admin page.
	 */
	public static function enqueue_admin_assets( $hook_suffix ) {
		if ( ! in_array( $hook_suffix, array( 'post.php', 'post-new.php', 'edit.php' ), true ) ) {
			return;
		}

		$screen = get_current_screen();
		if ( ! $screen || self::POST_TYPE !== $screen->post_type ) {
			return;
		}

		wp_enqueue_style(
			'mfi-admin',
			plugins_url( 'assets/css/admin.css', __FILE__ ),
			array(),
			self::VERSION
		);

		if ( 'edit.php' === $hook_suffix ) {
			return;
		}

		wp_enqueue_media();
		wp_enqueue_script(
			'mfi-admin',
			plugins_url( 'assets/js/admin.js', __FILE__ ),
			array( 'jquery' ),
			self::VERSION,
			true
		);

		wp_localize_script(
			'mfi-admin',
			'mfiAdmin',
			array(
				'frameTitle'    => __( 'Select an item file', 'multilingual-file-items' ),
				'frameButton'   => __( 'Use this file', 'multilingual-file-items' ),
				'replaceButton' => __( 'Replace File', 'multilingual-file-items' ),
				'selectButton'  => __( 'Select or Upload File', 'multilingual-file-items' ),
				'openFile'      => __( 'Open selected file', 'multilingual-file-items' ),
			)
		);
	}

	/**
	 * Add image and file columns to the list table.
	 *
	 * @param array $columns Existing columns.
	 * @return array
	 */
	public static function add_admin_columns( $columns ) {
		$new_columns = array();

		foreach ( $columns as $key => $label ) {
			if ( 'title' === $key ) {
				$new_columns['mfi_image'] = __( 'Image', 'multilingual-file-items' );
			}

			$new_columns[ $key ] = $label;

			if ( 'title' === $key ) {
				$new_columns['mfi_file'] = __( 'File', 'multilingual-file-items' );
			}
		}

		return $new_columns;
	}

	/**
	 * Render custom list-table columns.
	 *
	 * @param string $column  Column key.
	 * @param int    $post_id Post ID.
	 */
	public static function render_admin_columns( $column, $post_id ) {
		if ( 'mfi_image' === $column ) {
			if ( has_post_thumbnail( $post_id ) ) {
				echo get_the_post_thumbnail( $post_id, array( 60, 60 ), array( 'class' => 'mfi-admin-thumb' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			} else {
				echo '<span class="dashicons dashicons-format-image mfi-admin-placeholder" aria-hidden="true"></span>';
			}
			return;
		}

		if ( 'mfi_file' === $column ) {
			$file_id = absint( get_post_meta( $post_id, self::META_FILE_ID, true ) );
			$file_url = $file_id ? wp_get_attachment_url( $file_id ) : '';

			if ( ! $file_url ) {
				echo '&mdash;';
				return;
			}

			$file_name = basename( get_attached_file( $file_id ) ?: $file_url );
			echo '<a href="' . esc_url( $file_url ) . '" target="_blank" rel="noopener noreferrer">' . esc_html( $file_name ) . '</a>';
		}
	}

	/**
	 * Render the frontend file grid.
	 *
	 * Available attributes:
	 * - limit: Number of items. Default -1 (all).
	 * - language: Polylang language slug, "all", or blank for current language.
	 * - class: Additional wrapper class names.
	 * - orderby: menu_order, title, date, modified, or rand.
	 * - order: ASC or DESC.
	 *
	 * @param array $atts Shortcode attributes.
	 * @return string
	 */
	public static function render_shortcode( $atts ) {
		$atts = shortcode_atts(
			array(
				'limit'    => '-1',
				'language' => '',
				'class'    => '',
				'orderby'  => 'menu_order',
				'order'    => 'ASC',
			),
			$atts,
			'file_items'
		);

		$limit = intval( $atts['limit'] );
		if ( 0 === $limit || $limit < -1 ) {
			$limit = -1;
		}

		$allowed_orderby = array( 'menu_order', 'title', 'date', 'modified', 'rand' );
		$orderby         = in_array( $atts['orderby'], $allowed_orderby, true ) ? $atts['orderby'] : 'menu_order';
		$order           = 'DESC' === strtoupper( $atts['order'] ) ? 'DESC' : 'ASC';

		$query_args = array(
			'post_type'              => self::POST_TYPE,
			'post_status'            => 'publish',
			'posts_per_page'         => $limit,
			'orderby'                => $orderby,
			'order'                  => $order,
			'no_found_rows'          => true,
			'ignore_sticky_posts'    => true,
			'update_post_term_cache' => false,
			'suppress_filters'       => false,
			'meta_query'             => array(
				array(
					'key'     => self::META_FILE_ID,
					'compare' => 'EXISTS',
				),
			),
		);

		if ( 'menu_order' === $orderby ) {
			$query_args['orderby'] = array(
				'menu_order' => $order,
				'title'      => 'ASC',
			);
			unset( $query_args['order'] );
		}

		$language = sanitize_key( $atts['language'] );

		if ( function_exists( 'pll_current_language' ) && 'all' !== $language ) {
			if ( '' === $language ) {
				$current_language = pll_current_language( 'slug' );
				$language         = is_string( $current_language ) ? sanitize_key( $current_language ) : '';
			}

			if ( $language ) {
				$query_args['lang'] = $language;
			}
		}

		$query = new WP_Query( $query_args );

		if ( ! $query->have_posts() ) {
			return '';
		}

		wp_enqueue_style(
			'mfi-frontend',
			plugins_url( 'assets/css/frontend.css', __FILE__ ),
			array( 'dashicons' ),
			self::VERSION
		);

		$extra_classes = self::sanitize_classes( $atts['class'] );
		$classes       = trim( 'mfi-grid ' . implode( ' ', $extra_classes ) );

		ob_start();
		?>
		<div class="<?php echo esc_attr( $classes ); ?>">
			<?php
			while ( $query->have_posts() ) :
				$query->the_post();

				$post_id  = get_the_ID();
				$file_id  = absint( get_post_meta( $post_id, self::META_FILE_ID, true ) );
				$file_url = $file_id ? wp_get_attachment_url( $file_id ) : '';

				if ( ! $file_url ) {
					continue;
				}

				$title = get_the_title();
				?>
				<article class="mfi-item">
					<a class="mfi-item__link" href="<?php echo esc_url( $file_url ); ?>" target="_blank" rel="noopener noreferrer">
						<span class="mfi-item__image" aria-hidden="true">
							<?php echo self::get_item_image_html( $post_id, $file_id, $title ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						</span>
						<span class="mfi-item__title"><?php echo esc_html( $title ); ?></span>
					</a>
				</article>
			<?php endwhile; ?>
		</div>
		<?php
		wp_reset_postdata();

		return trim( ob_get_clean() );
	}

	/**
	 * Build the square item image or a file-type fallback.
	 *
	 * @param int    $post_id Post ID.
	 * @param int    $file_id Attachment ID.
	 * @param string $title   Item title.
	 * @return string
	 */
	private static function get_item_image_html( $post_id, $file_id, $title ) {
		$image_attributes = array(
			'class'   => 'mfi-item__img',
			'loading' => 'lazy',
			'alt'     => $title,
		);

		if ( has_post_thumbnail( $post_id ) ) {
			return get_the_post_thumbnail( $post_id, 'mfi-square', $image_attributes );
		}

		if ( wp_attachment_is_image( $file_id ) ) {
			return wp_get_attachment_image( $file_id, 'mfi-square', false, $image_attributes );
		}

		$file_path = get_attached_file( $file_id );
		$file_url  = wp_get_attachment_url( $file_id );
		$extension = pathinfo( $file_path ?: $file_url, PATHINFO_EXTENSION );
		$extension = $extension ? strtoupper( sanitize_text_field( $extension ) ) : __( 'FILE', 'multilingual-file-items' );

		return '<span class="mfi-item__fallback"><span class="dashicons dashicons-media-document" aria-hidden="true"></span><span class="mfi-item__extension">' . esc_html( $extension ) . '</span></span>';
	}

	/**
	 * Sanitize an arbitrary space-separated class list.
	 *
	 * @param string $class_string Raw classes.
	 * @return array
	 */
	private static function sanitize_classes( $class_string ) {
		$classes = preg_split( '/\s+/', (string) $class_string );
		$classes = array_filter( array_map( 'sanitize_html_class', $classes ) );
		return array_values( array_unique( $classes ) );
	}
}

MFI_Multilingual_File_Items::init();

register_activation_hook(
	__FILE__,
	function () {
		MFI_Multilingual_File_Items::register_post_type();
		flush_rewrite_rules();
	}
);

register_deactivation_hook( __FILE__, 'flush_rewrite_rules' );
