<?php
class Collections_API extends WP_REST_Controller {

  public function register_routes() {
    $version = '1';
    $namespace = 'ff/v' . $version;
    $base = 'collections';

    // /wp-json/ff/v1/collections?collection-types[]=[category slug]&collection-types[]=[category slug]
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
   * @param WP_REST_Request
   * @return WP_Error|WP_REST_Response
   */
  public function get_items( $request ) {

    $ct = $request->get_param('collection-types');

    //term query filters
    $category_args = [
      'taxonomy' => 'category',
      'fields' => 'all',
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
    $data = array();

    foreach( $terms as $category ) {
      if (!$ct || in_array($category->slug, $ct)) {

        //post query filters
        $post_args = [
          'post_type' => 'post',
          'post_status' => 'publish', //array('publish', 'pending', 'draft', 'auto-draft', 'future', 'private', 'inherit'),
          'fields'        => 'ids',
          'tax_query' => [
            [
              'taxonomy' => 'category',
              'field'     => 'id',
              'terms' => $category->term_id
            ]
          ]
        ];

        $data[$category->slug] = get_posts($post_args);
      }
    }

    return new WP_REST_Response( $data , 200 );
  }

  /**
   * Check if a given request has access to get items
   *
   * @param WP_REST_Request
   * @return WP_Error|bool
   */
  public function get_items_permissions_check( $request ) {
    return true;
  }
}
