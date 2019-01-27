<?php
/**
 * Created by PhpStorm.
 * User: Texel
 * Date: 2017/2/20
 * Time: 12:40
 */

defined( 'ABSPATH' ) || exit;

class GP_Services_Theme_Compat {
    public function __construct() {
        add_action( 'gp_setup_theme_compat', array( $this, 'is_member_services' ) );
    }

    public function is_member_services() {
        if ( ! bp_is_members_component() && ! bp_is_user() ) {
            return;
        }

        if ( bp_current_component() == 'services' ) {
            add_filter( 'bp_get_buddypress_template',                array( $this, 'single_template_hierarchy' ) );
            add_action( 'bp_template_include_reset_dummy_post_data', array( $this, 'single_dummy_post'    ) );
            add_filter( 'bp_replace_the_content',                    array( $this, 'single_dummy_content' ) );
        }
    }

    public function single_template_hierarchy( $templates ) {
        //$user_nicename = buddypress()->displayed_user->userdata->user_nicename;

        $new_templates = apply_filters( 'bp_template_hierarchy_members_single_item', array(
            'members/single/services.php'
        ) );

        $templates = array_merge( (array) $new_templates, $templates );

        return $templates;
    }

    public function single_dummy_post() {
        bp_theme_compat_reset_post( array(
            'ID'             => 0,
            'post_title'     => bp_get_displayed_user_fullname(),
            'post_author'    => 0,
            'post_date'      => 0,
            'post_content'   => '',
            'post_type'      => 'page',
            'post_status'    => 'publish',
            'is_page'        => true,
            'comment_status' => 'closed'
        ) );
    }

    public function single_dummy_content() {
        return gp_buffer_template_part( 'members/single/home', null, false );
    }
}