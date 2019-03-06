<?php

class Content_API extends WP_REST_Controller {

  public function register_routes() {
    $version = '1';
    $namespace = 'ff/v' . $version;
    $base = 'content';

    // /wp-json/ff/v1/content
    register_rest_route($namespace, '/' . $base, array(
      [
        'methods'   => WP_REST_Server::READABLE,
        'callback'  => [$this, 'get_items'],
        'permission_callback' => [$this, 'get_items_permissions_check'],
        'args' => []
      ]
    ));
  }

  /**
   * Get a collection of items
   *
   * @param WP_REST_Request $request Full data about the request.
   * @return WP_Error|WP_REST_Response
   */
  public function get_items( $request ) {

    $ct = $request->get_param('collection-types');

    //term query filters
    $category_args = [
      'taxonomy' => 'category',
      'fields' => $ct ? 'all' : 'ids',
      'meta_query' => [
        [
          'key'     => 'addToApi',
          'value'   => 'yes',
          'compare' => '=',
          'hide_empty' => true
        ],
      ],
    ];

    $term_ids = [];

    if ($ct) {

      //term ids of categories with addToApi='yes'
      $terms = get_terms($category_args);
      foreach( $terms as $term ) {
        if (in_array($term->slug, $ct)) {
          $term_ids[] = $term->term_id;
        }
      }

    } else {
  
      //term ids of categories with addToApi='yes'
      $term_ids = get_terms($category_args);
      
    }

    //post query filters
    $post_args = [
      'post_type' => 'post',
      'post_status' => 'publish', //array('publish', 'pending', 'draft', 'auto-draft', 'future', 'private', 'inherit'),
      'tax_query' => [
        [
          'taxonomy' => 'category',
          'field'     => 'id',
          'terms' => $term_ids
        ]
      ]
    ];

    $posts = get_posts($post_args);
    $data = array();

    foreach( $posts as $item ) {
      if ( ! function_exists( 'is_plugin_active' ) ) {
        require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
      }
      
      if ( is_plugin_active( 'advanced-custom-fields/acf.php' ) ) {
        $fields = (array) get_fields($item->ID);
        $item = (object) array_merge((array) $item, $fields);
      }

      $itemdata = $this->prepare_item_for_response( $item, $request );
      $data[$item->ID] = $this->prepare_response_for_collection( $itemdata );
    }

    return new WP_REST_Response( $data , 200 );
  }

  /**
   * Check if a given request has access to get items
   *
   * @param WP_REST_Request $request Full data about the request.
   * @return WP_Error|bool
   */
  public function get_items_permissions_check( $request ) {
    return true;
  }

  /**
   * Prepare the item for the REST response
   *
   * @param mixed $item WordPress representation of the item.
   * @param WP_REST_Request $request Request object.
   * @return mixed
   */
  public function prepare_item_for_response( $item, $request ) {
    $meta = get_post_meta($item->ID);

    if ( has_post_thumbnail($item) ) {
      $feat_image_url = wp_get_attachment_url(get_post_thumbnail_id($item));
      $item->feature_image_url = $feat_image_url;
    }

    return $item;
  }
}