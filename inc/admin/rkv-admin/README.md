# rkv-admin
An admin page and settings generation solution

## Usage

Start by cloning this repo into the project. Do not change the prefix or otherwise alter the files in the repo. 

Require the `init.php` file. This will check to see if it may have been used in another plugin or the theme before adding the autoloader.

The menu, page, setting section, and settings are all abstracted so it is possible to build only specific parts or to initialize a whole new page with admin menu, page output, sections, and settings all at once.

## Examples

### Add settings to existing page and section

~~~php
<?php
/**
 * Adds the settings page and options.
 * 
 * @package example-theme
 */

require_once get_template_directory() . '/inc/classes/admin/rkv-admin/init.php';

$settings = array(
	'setting_id' => array(
		'label'    => __( 'Text Field', 'example-theme' ),
		'default'  => '',
		'type'     => 'text',
		'sanitize' => 'no_html'
	),
	'select_id'  => array(
		'label'      => __( 'Select Field', 'example-theme' ),
		'descripton' => __( 'This is a description', 'example-theme' ),
		'default'    => '',
		'type'       => 'select',
        'sanitize'   => 'in_array',
		'options'    => array(
			''      => __( 'Select Color', 'example-theme' ),
			'red'   => __( 'Red', 'example-theme' ),
			'green' => __( 'Green', 'example-theme' ),
		)
	),
);

// Adds to the default section of the reading page.
new Rkv_Admin_Settings( 'reading', $settings );
~~~

### Add a new section and settings to an existing page.

~~~php
<?php
/**
 * Adds the settings page and options.
 * 
 * @package example-theme
 */

require_once get_template_directory() . '/inc/classes/admin/rkv-admin/init.php';

$setting_sections = array(
	'custom_section' => array(
		'title'       => __( 'This is a custom section', 'example-theme' ),
		'description' => __( 'This is a description', 'example-theme' ),
	),
);

$settings = array(
	'setting_id' => array(
        'label'    => __( 'Text Field', 'example-theme' ),
        'default'  => '',
        'type'     => 'text',
        'sanitize' => 'no_html'
    ),
    'select_id'  => array(
        'label'      => __( 'Select Field', 'example-theme' ),
        'descripton' => __( 'This is a description', 'example-theme' ),
        'default'    => '',
        'type'       => 'select',
        'sanitize'   => 'in_array',
        'options'    => array(
            ''      => __( 'Select Color', 'example-theme' ),
            'red'   => __( 'Red', 'example-theme' ),
            'green' => __( 'Green', 'example-theme' ),
        )
    ),
	'email' => array(
		'label'    => __( 'Email', 'example-theme' ),
		'default'  => '',
		'type'     => 'text',
		'sanitize' => 'email_address',
		'section'  => 'custom_section',
	),
	'select_id_2'  => array(
		'label'      => __( 'Select Field 2', 'example-theme' ),
		'descripton' => __( 'This is a description', 'example-theme' ),
		'default'    => '',
		'type'       => 'select',
		'section'    => 'custom_section',
        'sanitize'   => 'in_array',
		'options'    => array(
			''         => __( 'Select Shape', 'example-theme' ),
			'square'   => __( 'Square', 'example-theme' ),
			'triangle' => __( 'Triangle', 'example-theme' ),
		)
	),
);

new Rkv_Admin_Settings( 'reading', $settings );
~~~

### Add all the things

~~~php
<?php
/**
 * Adds the settings page and options.
 * 
 * @package example-theme
 */

require_once get_template_directory() . '/inc/classes/admin/rkv-admin/init.php';

$menu_ops = array(
	'main_menu' => array(
		'page_title' => __( 'Setting Page Title', 'example-theme' ),
		'menu_title' => __( 'Setting Menu Title', 'example-theme' ),
		'icon_url'   => 'dashicons-randomize',
		'position'   => 52.899,
	)
);

$page_ops = array(
	'type'              => 'form',
	'description'       => __( 'This is the page description', 'example-theme' ),
	'save_button_text'  => __( 'Save', 'example-theme' ),
);

// Optional if additional sections are required
$setting_sections = array(
	'custom_section' => array(
		'title'       => __( 'This is a custom section', 'example-theme' ),
		'description' => __( 'This is a description', 'example-theme' ),
	),
);

$settings = array(
	'setting_id' => array(
		'label'    => __( 'Text Field', 'example-theme' ),
		'default'  => '',
		'type'     => 'text',
		'sanitize' => 'no_html'
	),
	'select_id'  => array(
		'label'      => __( 'Select Field', 'example-theme' ),
		'descripton' => __( 'This is a description', 'example-theme' ),
		'default'    => '',
		'type'       => 'select',
		'sanitize'   => 'in_array',
		'options'    => array(
			''      => __( 'Select Color', 'example-theme' ),
			'red'   => __( 'Red', 'example-theme' ),
			'green' => __( 'Green', 'example-theme' ),
		)
	),
	'email' => array(
		'label'    => __( 'Email', 'example-theme' ),
		'default'  => '',
		'type'     => 'text',
		'sanitize' => 'email_address',
		'section'  => 'custom_section',
	),
	'select_id_2'  => array(
		'label'      => __( 'Select Field 2', 'example-theme' ),
		'descripton' => __( 'This is a description', 'example-theme' ),
		'default'    => '',
		'type'       => 'select',
		'section'    => 'custom_section',
		'sanitize'   => 'in_array',
		'options'    => array(
			''         => __( 'Select Shape', 'example-theme' ),
			'square'   => __( 'Square', 'example-theme' ),
			'triangle' => __( 'Triangle', 'example-theme' ),
		)
	),
);

$my_admin = new Rkv_Admin( 'my-admin', $menu_ops, $page_ops, $settings, $setting_sections );
~~~

### Add a page with no form using.

~~~php
<?php
/**
 * Adds the settings page and options.
 * 
 * @package example-theme
 */

require_once get_template_directory() . '/inc/classes/admin/rkv-admin/init.php';

$menu_ops = array(
	'main_menu' => array(
		'page_title' => __( 'Setting Page Title', 'example-theme' ),
		'menu_title' => __( 'Setting Menu Title', 'example-theme' ),
		'icon_url'   => 'dashicons-randomize',
		'position'   => 52.899,
	)
);

$page_ops = array(
	'type'              => 'form',
	'description'       => __( 'This is the page description', 'example-theme' ),
);

$my_admin = new Rkv_Admin( 'my-admin', $menu_ops, $page_ops );
/**
 * Outputs custom content on the admin page.
 *
 * @param array $page_ops The options for the page.
 */
function example_theme_my_admin_content( $page_ops ) {
	printf( '<p>%s</p>', esc_html__( 'This shows up after the page descripton. The $page_ops variable is:' ) );
	echo '<pre><code>'; var_dump( $page_ops ); echo '</code></pre>';
}
add_action( 'rkv_before_page_my-admin', 'example_theme_my_admin_content' );
~~~
## Notes:

 - Always supply the `sanitize` option to the settings. This will default to "text_input" but there are better options.
 - Use the most limiting option available. 
   - For select and radio it is almost always appropriate to use `in_array` as this will force the option to match the `options` array.
   - For checkboxes the correct option is almost always `one_zero` since the value for "checked" is 1.
   - URL fields should be `url`
   - ...
 - When adding a page, it is easiest to use the `Rkv_Admin()` class as the menu and page are interdependent and this gets setup automatically.
 - It is possible to leave $menu_ops and $page_ops empty to use `Rkv_Admin()` to add settings and/or sections without creating a page.
 