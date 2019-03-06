<?php 

defined( 'ABSPATH' ) or die;

function ff_api_add_meta_fields( $taxonomy ) { ?>
    <div class="form-field term-group">
        <label for="addToApi">
          <?php _e( 'Add to Api', 'codilight-lite' ); ?> <input type="checkbox" id="addToApi" name="addToApi" value="yes" />
        </label>
    </div><?php
}
add_action( 'category_add_form_fields', 'ff_api_add_meta_fields', 10, 2 );

function ff_api_edit_meta_fields( $term, $taxonomy ) {
    $addToApi = get_term_meta( $term->term_id, 'addToApi', true ); ?>
    
    <tr class="form-field term-group-wrap">
        <th scope="row">
            <label for="addToApi"><?php _e( 'Add to Api', 'codilight-lite' ); ?></label>
        </th>
        <td>
            <input type="checkbox" id="addToApi" name="addToApi" value="yes" <?php echo ( $addToApi ) ? checked( $addToApi, 'yes' ) : ''; ?>/>
        </td>
    </tr><?php
}
add_action( 'category_edit_form_fields', 'ff_api_edit_meta_fields', 10, 2 );

// Save custom meta
function ff_api_save_taxonomy_meta( $term_id, $tag_id ) {
    if ( isset( $_POST[ 'addToApi' ] ) ) {
        update_term_meta( $term_id, 'addToApi', 'yes' );
    } else {
        update_term_meta( $term_id, 'addToApi', '' );
    }
}
add_action( 'created_category', 'ff_api_save_taxonomy_meta', 10, 2 );
add_action( 'edited_category', 'ff_api_save_taxonomy_meta', 10, 2 );