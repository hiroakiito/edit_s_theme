<?php
/**
 * _sTheme functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package _sTheme
 */

//  リリースする際にテーマのバージョン番号を更新する
if ( ! defined( '_S_VERSION' ) ) {
	define( '_S_VERSION', '1.0.0' );
}

if ( ! function_exists( 'sample_theme_setup' ) ) :
	/**
	 *  テーマのデフォルト設定を行う
	 * この関数はafter_set_up_themeによってフックされる
	 * 初期化フックの前に実行される。
	 */
	function sample_theme_setup() {
		/*
		 * テーマを翻訳できるようにする。
		 * 翻訳は/ languages /ディレクトリに保存でされる。
		 * _sThemeに基づいてテーマを構築している場合は、検索と置換を使用します
		 * 「sample-theme」をすべてのテンプレートファイルのテーマの名前に変更します。
		 */
		load_theme_textdomain( 'sample-theme', get_template_directory() . '/languages' );

		// デフォルトの投稿とコメントのRSSフィードリンクをヘッド情報に追加します。
		add_theme_support( 'automatic-feed-links' );

		/*
		* WordPressにドキュメントのタイトルを管理させます。
		*テーマのサポートを追加することにより、このテーマは使用しないことを宣言します
		*ドキュメントヘッドにハードコードされた<title>タグ。WordPressが
		*提供してください。
		 */
		add_theme_support( 'title-tag' );

		/*
		 * 投稿とページの投稿サムネイルのサポートを有効にします。
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus(
			array(
				'menu-1' => esc_html__( 'Primary', 'sample-theme' ),
			)
		);

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support(
			'html5',
			array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
				'style',
				'script',
			)
		);

		// Set up the WordPress core custom background feature.
		add_theme_support(
			'custom-background',
			apply_filters(
				'sample_theme_custom_background_args',
				array(
					'default-color' => 'ffffff',
					'default-image' => '',
				)
			)
		);

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		/**
		 * Add support for core custom logo.
		 *
		 * @link https://codex.wordpress.org/Theme_Logo
		 */
		add_theme_support(
			'custom-logo',
			array(
				'height'      => 250,
				'width'       => 250,
				'flex-width'  => true,
				'flex-height' => true,
			)
		);
	}
endif;
add_action( 'after_setup_theme', 'sample_theme_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function sample_theme_content_width() {
	// This variable is intended to be overruled from themes.
	// Open WPCS issue: {@link https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards/issues/1043}.
	// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
	$GLOBALS['content_width'] = apply_filters( 'sample_theme_content_width', 640 );
}
add_action( 'after_setup_theme', 'sample_theme_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function sample_theme_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Sidebar', 'sample-theme' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here.', 'sample-theme' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'sample_theme_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function sample_theme_scripts() {
	wp_enqueue_style( 'sample-theme-style', get_stylesheet_uri(), array(), _S_VERSION );
	wp_enqueue_style( 'app', get_template_directory_uri() . '/css/app.css', array(), _S_VERSION );
	wp_style_add_data( 'sample-theme-style', 'rtl', 'replace' );

	wp_enqueue_script( 'sample-theme-navigation', get_template_directory_uri() . '/js/navigation.js', array(), _S_VERSION, true );

	wp_enqueue_script( 'sample-theme-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), _S_VERSION, true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'sample_theme_scripts' );

/* パンくずリスト表示用*/
function breadcrumb() {
	$home = '<li class="bread-item"><a href="'.get_bloginfo('url').'" >HOME</a></li>';

	echo '<ul class="container bread-container">';
	if ( is_front_page() ) {
	// トップページの場合
	}
	else if ( is_category() ) {
	// カテゴリページの場合
	}
	else if ( is_archive() ) {
	// 月別アーカイブ・タグページの場合
	}
	else if ( is_single() ) {
	// 投稿ページの場合
		echo $home;
		$cat = get_the_category();
		if( isset($cat[0]->cat_ID) ) $cat_id = $cat[0]->cat_ID;
		$cat_list = array();
		while ($cat_id != 0){
				$cat = get_category( $cat_id );
				$cat_link = get_category_link( $cat_id );
				array_unshift( $cat_list, '<li class="bread-item">><a href="'.$cat_link.'">'.$cat->name.'</a>></li>' );
				$cat_id = $cat->parent;
		}
		foreach($cat_list as $value){
				echo $value;
		}
		the_title('<li class="bread-item">', '</li>');

	}
	else if( is_page() ) {
	// 固定ページの場合
	}
	else if( is_search() ) {
	// 検索ページの場合
	}
	else if( is_404() ) {
	// 404ページの場合
	}
	echo "</ul>";
}

// コメントスタイルの編集用
function comment_callback($comment, $args, $depth) {
  $GLOBALS['comment'] = $comment; ?>
   <li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
     <div id="comment-<?php comment_ID(); ?>">
      <div class="comment-author vcard">
         <?php echo get_avatar($comment,$size='48',$default='<path_to_url>' ); ?>
			<div class="comment-right">
         <?php printf(__('<cite class="fn">%s</cite>'), get_comment_author_link()) ?>
 
      <div class="comment-meta commentmetadata">
				<a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ) ?>">
					<?php printf(__('%1$s'), get_comment_date('Y.m.d')) ?>
				</a><?php edit_comment_link(__('(Edit)'),'  ','') ?>
			</div>
 
      <?php comment_text() ?>
			</div>
			</div>
      <div class="reply">
         <?php comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
      </div>
     </div>
<?php
        }

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}
