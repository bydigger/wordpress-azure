<?php

class Inwave_Icon_Menu {

    /*--------------------------------------------*
     * Constructor
     *--------------------------------------------*/

    /**
     * Initializes the plugin by setting localization, filters, and administration functions.
     */
    function __construct() {

        // add custom menu fields to menu
        add_filter( 'wp_setup_nav_menu_item', array( $this, 'inwave_add_custom_nav_fields' ) );

        // save menu custom fields
        add_action( 'wp_update_nav_menu_item', array( $this, 'inwave_update_custom_nav_fields'), 10, 3 );

        // edit menu walker
        add_filter( 'wp_edit_nav_menu_walker', array( $this, 'inwave_edit_walker'), 10, 2 );

    } // end constructor

    /**
     * Add custom fields to $item nav object
     * in order to be used in custom Walker
     *
     * @access      public
     * @since       1.0
     * @return      void
     */
    function inwave_add_custom_nav_fields( $menu_item ) {

        $menu_item->icon = get_post_meta( $menu_item->ID, '_menu_item_icon', true );
        $menu_item->bg_submenu = get_post_meta( $menu_item->ID, '_bg_submenu', true );
        $menu_item->number_column = get_post_meta( $menu_item->ID, '_number_column', true );
        $menu_item->number_column ? $menu_item->number_column : 1;

        return $menu_item;

    }

    /**
     * Save menu custom fields
     *
     * @access      public
     * @since       1.0
     * @return      void
     */
    function inwave_update_custom_nav_fields( $menu_id, $menu_item_db_id, $args ) {

        // Check if element is properly sent
        if (!isset($_REQUEST['menu-item-icon'])){
            $_REQUEST['menu-item-icon'] = "";
        }else{
            if ( is_array( $_REQUEST['menu-item-icon']) ) {
                $icon_value = $_REQUEST['menu-item-icon'][$menu_item_db_id];
                update_post_meta($menu_item_db_id, '_menu_item_icon', sanitize_text_field($icon_value));
            }
        }

        if (!isset($_REQUEST['bg-submenu'])){
            $_REQUEST['bg-submenu'] = "";
        }else{
            if ( is_array( $_REQUEST['bg-submenu']) ) {
                $bg_submenu = $_REQUEST['bg-submenu'][$menu_item_db_id];
                update_post_meta($menu_item_db_id, '_bg_submenu', sanitize_text_field($bg_submenu));
            }
        }

        if (!isset($_REQUEST['number-column'])){
            $_REQUEST['number-column'] = "";
        }else{
            if ( is_array( $_REQUEST['number-column']) ) {
                $number_column = $_REQUEST['number-column'][$menu_item_db_id];
                update_post_meta($menu_item_db_id, '_number_column', sanitize_text_field($number_column));
            }
        }

    }

    /**
     * Define new Walker edit
     *
     * @access      public
     * @since       1.0
     * @return      void
     */
    function inwave_edit_walker($walker,$menu_id) {

        return 'Inwave_Walker_Nav_Menu_Edit_Custom';

    }

}
// instantiate plugin's class
new Inwave_Icon_Menu();

class Inwave_Walker_Nav_Menu_Edit_Custom extends Walker_Nav_Menu  {
    /**
     * @see Walker_Nav_Menu::start_lvl()
     * @since 3.0.0
     *
     * @param string $output Passed by reference.
     */
    function start_lvl(&$output, $depth = 0, $args = array()) {
    }

    /**
     * @see Walker_Nav_Menu::end_lvl()
     * @since 3.0.0
     *
     * @param string $output Passed by reference.
     */
    function end_lvl(&$output, $depth = 0, $args = array()) {

    }

    /**
     * @see Walker::start_el()
     * @since 3.0.0
     *
     * @param string $output Passed by reference. Used to append additional content.
     * @param object $item Menu item data object.
     * @param int $depth Depth of menu item. Used for padding.
     * @param object $args
     */
    function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0) {

        global $_wp_nav_menu_max_depth;

        $_wp_nav_menu_max_depth = $depth > $_wp_nav_menu_max_depth ? $depth : $_wp_nav_menu_max_depth;

        $indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

        ob_start();
        $item_id = esc_attr( $item->ID );
        $removed_args = array(
            'action',
            'customlink-tab',
            'edit-menu-item',
            'menu-item',
            'page-tab',
            '_wpnonce',
        );

        $original_title = '';
        if ( 'taxonomy' == $item->type ) {
            $original_title = get_term_field( 'name', $item->object_id, $item->object, 'raw' );
            if ( is_wp_error( $original_title ) )
                $original_title = false;
        } elseif ( 'post_type' == $item->type ) {
            $original_object = get_post( $item->object_id );
            $original_title = $original_object->post_title;
        }

        $classes = array(
            'menu-item menu-item-depth-' . $depth,
            'menu-item-' . esc_attr( $item->object ),
            'menu-item-edit-' . ( ( isset( $_GET['edit-menu-item'] ) && $item_id == $_GET['edit-menu-item'] ) ? 'active' : 'inactive'),
        );

        $title = $item->title;

        if ( ! empty( $item->_invalid ) ) {
            $classes[] = 'menu-item-invalid';
            /* translators: %s: title of menu item which is invalid */
            $title = sprintf( esc_html__( '%s (Invalid)','inmedical' ), $item->title );
        } elseif ( isset( $item->post_status ) && 'draft' == $item->post_status ) {
            $classes[] = 'pending';
            /* translators: %s: title of menu item in draft status */
            $title = sprintf( esc_html__('%s (Pending)','inmedical'), $item->title );
        }

        $title = empty( $item->label ) ? $title : $item->label;

        ?>
    <li id="menu-item-<?php echo esc_attr($item_id); ?>" class="<?php echo implode(' ', $classes ); ?>">
        <dl class="menu-item-bar">
            <dt class="menu-item-handle">
                <span class="item-title"><?php echo esc_html( $title ); ?></span>
        <span class="item-controls">
            <span class="item-type"><?php echo esc_html( $item->type_label ); ?></span>
            <span class="item-order hide-if-js">
                <a href="<?php
                echo wp_nonce_url(
                    add_query_arg(
                        array(
                            'action' => 'move-up-menu-item',
                            'menu-item' => $item_id,
                        ),
                        remove_query_arg($removed_args, admin_url( 'nav-menus.php' ) )
                    ),
                    'move-menu_item'
                );
                ?>" class="item-move-up"><abbr title="<?php esc_attr_e('Move up','inmedical'); ?>">&#8593;</abbr></a>
                |
                <a href="<?php
                echo wp_nonce_url(
                    add_query_arg(
                        array(
                            'action' => 'move-down-menu-item',
                            'menu-item' => $item_id,
                        ),
                        remove_query_arg($removed_args, admin_url( 'nav-menus.php' ) )
                    ),
                    'move-menu_item'
                );
                ?>" class="item-move-down"><abbr title="<?php esc_attr_e('Move down','inmedical'); ?>">&#8595;</abbr></a>
            </span>
            <a class="item-edit" id="edit-<?php echo esc_attr($item_id); ?>" title="<?php esc_attr_e('Edit Menu Item','inmedical'); ?>" href="<?php
            echo ( isset( $_GET['edit-menu-item'] ) && $item_id == $_GET['edit-menu-item'] ) ? admin_url( 'nav-menus.php' ) : add_query_arg( 'edit-menu-item', $item_id, remove_query_arg( $removed_args, admin_url( 'nav-menus.php#menu-item-settings-' . $item_id ) ) );
            ?>"><?php esc_html_e( 'Edit Menu Item','inmedical' ); ?></a>
        </span>
            </dt>
        </dl>

        <div class="menu-item-settings wp-clearfix" id="menu-item-settings-<?php echo esc_attr($item_id); ?>">
            <?php if( 'custom' == $item->type ) : ?>
                <p class="field-url description description-wide">
                    <label for="edit-menu-item-url-<?php echo esc_attr($item_id); ?>">
                        <?php esc_html_e( 'URL','inmedical' ); ?><br />
                        <input type="text" id="edit-menu-item-url-<?php echo esc_attr($item_id); ?>" class="widefat code edit-menu-item-url" name="menu-item-url[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr( $item->url ); ?>" />
                    </label>
                </p>
            <?php endif; ?>
            <p class="description description-thin">
                <label for="edit-menu-item-title-<?php echo esc_attr($item_id); ?>">
                    <?php esc_html_e( 'Navigation Label','inmedical' ); ?><br />
                    <input type="text" id="edit-menu-item-title-<?php echo esc_attr($item_id); ?>" class="widefat edit-menu-item-title" name="menu-item-title[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr( $item->title ); ?>" />
                </label>
            </p>
            <p class="description description-thin">
                <label for="edit-menu-item-attr-title-<?php echo esc_attr($item_id); ?>">
                    <?php esc_html_e( 'Title Attribute','inmedical' ); ?><br />
                    <input type="text" id="edit-menu-item-attr-title-<?php echo esc_attr($item_id); ?>" class="widefat edit-menu-item-attr-title" name="menu-item-attr-title[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr( $item->post_excerpt ); ?>" />
                </label>
            </p>
            <p class="field-link-target description">
                <label for="edit-menu-item-target-<?php echo esc_attr($item_id); ?>">
                    <input type="checkbox" id="edit-menu-item-target-<?php echo esc_attr($item_id); ?>" value="_blank" name="menu-item-target[<?php echo esc_attr($item_id); ?>]"<?php checked( $item->target, '_blank' ); ?> />
                    <?php esc_html_e( 'Open link in a new window/tab','inmedical' ); ?>
                </label>
            </p>
            <p class="field-css-classes description description-thin">
                <label for="edit-menu-item-classes-<?php echo esc_attr($item_id); ?>">
                    <?php esc_html_e( 'CSS Classes (optional)','inmedical' ); ?><br />
                    <input type="text" id="edit-menu-item-classes-<?php echo esc_attr($item_id); ?>" class="widefat code edit-menu-item-classes" name="menu-item-classes[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr( implode(' ', $item->classes ) ); ?>" />
                </label>
            </p>
            <p class="field-xfn description description-thin">
                <label for="edit-menu-item-xfn-<?php echo esc_attr($item_id); ?>">
                    <?php esc_html_e( 'Link Relationship (XFN)','inmedical' ); ?><br />
                    <input type="text" id="edit-menu-item-xfn-<?php echo esc_attr($item_id); ?>" class="widefat code edit-menu-item-xfn" name="menu-item-xfn[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr( $item->xfn ); ?>" />
                </label>
            </p>
            <p class="field-description description description-wide">
                <label for="edit-menu-item-description-<?php echo esc_attr($item_id); ?>">
                    <?php esc_html_e( 'Description','inmedical' ); ?><br />
                    <textarea id="edit-menu-item-description-<?php echo esc_attr($item_id); ?>" class="widefat edit-menu-item-description" rows="3" cols="20" name="menu-item-description[<?php echo esc_attr($item_id); ?>]"><?php echo esc_html( $item->description ); // textarea_escaped ?></textarea>
                    <span class="description"><?php esc_html_e('The description will be displayed in the menu if the current theme supports it.','inmedical'); ?></span>
                </label>
            </p>
            <?php
            /* New fields insertion starts here */
            ?>
            <p class="field-custom description description-thin">
                <label for="edit-menu-item-icon-<?php echo esc_attr($item_id); ?>">
                    <?php esc_html_e( 'Input Icon','inmedical' ); ?><br />
                    <input class="iw-icon-menu2 widefat " type="text" id="edit-menu-item-icon-<?php echo esc_attr($item_id); ?>" class="widefat code edit-menu-item-custom" name="menu-item-icon[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr( $item->icon ); ?>" />
                </label>
            </p>
            <p class="field-custom-bg-menu description description-thin">
                <label for="edit-number-columns-<?php echo esc_attr($item_id); ?>">
                    <?php esc_html_e( 'Number Column','inmedical' ); ?><br />
                    <select name="number-column[<?php echo esc_attr($item_id); ?>]" class="menu-number-column">
                        <option value='1' <?php echo esc_attr( $item->number_column ) == '1' ? 'selected' : ''; ?>>1</option>
                        <option value='2' <?php echo esc_attr( $item->number_column ) == '2' ? 'selected' : ''; ?>>2</option>
                        <option value='3' <?php echo esc_attr( $item->number_column ) == '3' ? 'selected' : ''; ?>>3</option>
                        <option value='4' <?php echo esc_attr( $item->number_column ) == '4' ? 'selected' : ''; ?>>4</option>
                    </select>
                </label>
            </p>
            <p class="field-custom-bg-menu description description-wide">
                <label for="edit-bg-submenu-<?php echo esc_attr($item_id); ?>">
                    <?php esc_html_e( 'ID Image Background Submenu','inmedical' ); ?><br />
                    <input class="iw-bg-submenu" type="text" id="edit-bg-submenu-<?php echo esc_attr($item_id); ?>" class="widefat code edit-bg-submenu-item-custom" name="bg-submenu[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr( $item->bg_submenu ); ?>" />
                    <button class="set_custom_images button"><?php echo esc_html__('Set Image', 'inmedical'); ?></button>
                </label>
            </p>
            <?php
            /* New fields insertion ends here */
            ?>
            <div class="menu-item-actions description-wide submitbox">
                <?php if( 'custom' != $item->type && $original_title !== false ) : ?>
                    <p class="link-to-original">
                        <?php printf( esc_html__('Original: %s','inmedical'), '<a href="' . esc_url( $item->url ) . '">' . esc_html( $original_title ) . '</a>' ); ?>
                    </p>
                <?php endif; ?>
                <a class="item-delete submitdelete deletion" id="delete-<?php echo esc_attr($item_id); ?>" href="<?php
                echo wp_nonce_url(
                    add_query_arg(
                        array(
                            'action' => 'delete-menu-item',
                            'menu-item' => $item_id,
                        ),
                        remove_query_arg($removed_args, admin_url( 'nav-menus.php' ) )
                    ),
                    'delete-menu_item_' . $item_id
                ); ?>"><?php esc_html_e('Remove','inmedical'); ?></a> <span class="meta-sep"> | </span> <a class="item-cancel submitcancel" id="cancel-<?php echo esc_attr($item_id); ?>" href="<?php echo esc_url( add_query_arg( array('edit-menu-item' => $item_id, 'cancel' => time()), remove_query_arg( $removed_args, admin_url( 'nav-menus.php' ) ) ) );
                ?>#menu-item-settings-<?php echo esc_attr($item_id); ?>"><?php esc_html_e('Cancel','inmedical'); ?></a>
            </div>
            <input class="menu-item-data-db-id" type="hidden" name="menu-item-db-id[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr($item_id); ?>" />
            <input class="menu-item-data-object-id" type="hidden" name="menu-item-object-id[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr( $item->object_id ); ?>" />
            <input class="menu-item-data-object" type="hidden" name="menu-item-object[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr( $item->object ); ?>" />
            <input class="menu-item-data-parent-id" type="hidden" name="menu-item-parent-id[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr( $item->menu_item_parent ); ?>" />
            <input class="menu-item-data-position" type="hidden" name="menu-item-position[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr( $item->menu_order ); ?>" />
            <input class="menu-item-data-type" type="hidden" name="menu-item-type[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr( $item->type ); ?>" />
        </div><!-- .menu-item-settings-->
        <ul class="menu-item-transport"></ul>
        <?php
        $output .= ob_get_clean();
    }

}

class Inwave_Nav_Walker extends Walker_Nav_Menu{
    //start of the sub menu wrap
    public function start_lvl( &$output, $depth = 0, $args = array() ) {
        $output .= '<ul class="sub-menu child-nav dropdown-nav">';
    }
    //end of the sub menu wrap
    public function end_lvl( &$output, $depth = 0, $args = array() ) {
        $output .= '</ul>';
    }
    public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
        $indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

        $classes = empty( $item->classes ) ? array() : (array) $item->classes;
        $classes[] = 'menu-item-' . $item->ID;
        $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth ) );
        $class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';


        $id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args, $depth );
        $id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

        $output .= $indent . '<li' . $id . $class_names .'>';

        $atts = array();
        $atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
        $atts['target'] = ! empty( $item->target )     ? $item->target     : '';
        $atts['rel']    = ! empty( $item->xfn )        ? $item->xfn        : '';
        $atts['href']   = ! empty( $item->url )        ? $item->url        : '';


        $atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );

        $attributes = '';
        foreach ( $atts as $attr => $value ) {
            if ( ! empty( $value ) ) {
                $value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
                $attributes .= ' ' . $attr . '="' . $value . '"';
            }
        }

        $icon_output = empty($item->icon) ? '': '<i class="'.$item->icon.'"></i>';

        $number_column = '';
        if($depth == 0){
            $number_column = empty($item->number_column) ? '': 'number-'.$item->number_column.'-column';
        }

        $item_output = ( isset($args->before) ? $args->before : '');

        if($item->bg_submenu) {
            $item_output .= '<a class="bg-menu-item ' . $number_column .'" data-image="'. $item->bg_submenu . '"';
            $item_output .= $attributes . '>';
        }else{
            $item_output .= '<a class="' . $number_column . '"' . $attributes .' >';
        }
        if($depth == 0){
            $item_output .= '<strong>';
        }
        $item_output .= $icon_output;
        /** This filter is documented in wp-includes/post-template.php */
        $item_output .= (isset($args->link_before) ? $args->link_before : '') . apply_filters( 'the_title', $item->title, $item->ID ) . (isset($args->link_after) ? $args->link_after : '');

//        if(in_array('menu-item-has-children',$classes)){
//            $item_output .= '<small class="icon-arrow"></small>';
//        }
        if($depth == 0){
            $item_output .= '</strong>';
        }
        $item_output .= '</a>';
        $item_output .= ( isset($args->after) ? $args->after : '');

        $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
    }
}

class Inwave_Nav_Walker_Mobile extends Walker_Nav_Menu{
    //start of the sub menu wrap
    public function start_lvl( &$output, $depth = 0, $args = array() ) {
        $output .= '<ul class="sub-menu child-nav dropdown-nav">';
    }
    //end of the sub menu wrap
    public function end_lvl( &$output, $depth = 0, $args = array() ) {
        $output .= '</ul>';
    }
    public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
        $indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

        $classes = empty( $item->classes ) ? array() : (array) $item->classes;
        $classes[] = 'menu-item-' . $item->ID;

        $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth ) );
        $class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';


        $id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args, $depth );
        $id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

        $output .= $indent . '<li' . $id . $class_names .'>';

        $atts = array();
        $atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
        $atts['target'] = ! empty( $item->target )     ? $item->target     : '';
        $atts['rel']    = ! empty( $item->xfn )        ? $item->xfn        : '';
        $atts['href']   = ! empty( $item->url )        ? $item->url        : '';


        $atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );

        $attributes = '';
        foreach ( $atts as $attr => $value ) {
            if ( ! empty( $value ) ) {
                $value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
                $attributes .= ' ' . $attr . '="' . $value . '"';
            }
        }

        $item_output = ( isset($args->before) ? $args->before : '');
        $item_output .= '<a'. $attributes .'>';
        /** This filter is documented in wp-includes/post-template.php */
        $item_output .= (isset($args->link_before) ? $args->link_before : '') . apply_filters( 'the_title', $item->title, $item->ID ) . (isset($args->link_after) ? $args->link_after : '');
        $item_output .= '</a>';
        $item_output .= ( isset($args->after) ? $args->after : '');

        $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
    }
}