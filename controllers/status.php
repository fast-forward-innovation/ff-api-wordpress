<?php

class Status_API extends WP_REST_Controller {

  public function register_routes() {
    $version = '1';
    $namespace = 'ff/v' . $version;
    $base = 'status';

    // /wp-json/ff/v1/status
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

    //term query filters
    $category_args = [
      'taxonomy' => 'category',
      'fields' => 'ids',
      'meta_query' => [
        [
          'key'     => 'addToApi',
          'value'   => 'yes',
          'compare' => '=',
          'hide_empty' => true
        ],
      ],
    ];

    //term ids of categories with addToApi='yes'
    $terms = get_terms($category_args);

    //post query filters
    $post_args = [
      'post_type' => 'post',
      'post_status' => 'publish', //array('publish', 'pending', 'draft', 'auto-draft', 'future', 'private', 'inherit'),
      'orderby' => 'modified',
      'numberposts' => 1,
      'order' => 'DESC',
      'tax_query' => [
        [
          'taxonomy' => 'category',
          'field'     => 'id',
          'terms' => $terms
        ]
      ]
    ];

    $posts = get_posts($post_args);
    $data = array();

    if (count($posts)) {
      $data['last_update'] = mysql2date('U', $posts[0]->post_modified);
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
}
