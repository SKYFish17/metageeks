<div class="woocommerce_checkout_wrapper_review__contents">
<?php
do_action( 'woocommerce_review_order_before_cart_contents' );

foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {

  $_product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );

  if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_checkout_cart_item_visible', true, $cart_item, $cart_item_key ) ) {

    $id = $cart_item['data']->get_id();
    $product = wc_get_product( $id );
    $is_variation = $product->get_type() === 'variation' ? true : false;

    if ( $is_variation ) {
      $product_max_qty = get_post_meta( $id, 'variation_maximum_allowed_quantity', true ) ? get_post_meta( $id, 'variation_maximum_allowed_quantity', true ) : '';
      $product_min_qty = get_post_meta( $id, 'variation_minimum_allowed_quantity', true ) ? get_post_meta( $id, 'variation_minimum_allowed_quantity', true ) : 1;
    }
    else {
      $product_max_qty  = $cart_item['data']->get_meta('maximum_allowed_quantity') ? $cart_item['data']->get_meta('maximum_allowed_quantity') : '';
      $product_min_qty  = $cart_item['data']->get_meta('minimum_allowed_quantity') ? $cart_item['data']->get_meta('minimum_allowed_quantity') : 1 ;
    }
    $product_stock = $_product->get_stock_quantity();
    if ( ! empty( $product_stock ) &&  empty( $product_max_qty ) ) {
      $product_max_qty = $product_stock;
    }
    elseif ( ! empty( $product_stock ) &&  ! empty( $product_max_qty ) ) {
      if ( $product_stock < $product_max_qty ) {
        $product_max_qty = $product_stock;
      }
    }
    $product_quantity = $cart_item['quantity'];
    $product_availability = $cart_item[ 'data' ]->get_availability();
  ?>
    <div class="<?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>" <?php echo $product_max_qty ? 'data-product-max-qty="'.$product_max_qty.'"' : '' ?>>
      <a href="<?php echo $_product->get_permalink() ?>" class="product-image">
        <?php $thumb = get_the_post_thumbnail_url( $_product->get_id(), 'shop_catalog' ); ?>
        <?php if ( ! $thumb ): ?>
          <?php $thumb = wc_placeholder_img_src( 'thumbnail' ); ?>
        <?php endif; ?>
        <img class="image" src="<?php echo "{$thumb}"; ?>" alt="product-image">
      </a>
      <div class="product-name">
        <a href="<?php echo $_product->get_permalink() ?>">
          <?php echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) ) . '&nbsp;'; ?>
          <?php //echo apply_filters( 'woocommerce_checkout_cart_item_quantity', ' <strong class="product-quantity">' . sprintf( '&times;&nbsp;%s', $cart_item['quantity'] ) . '</strong>', $cart_item, $cart_item_key ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
        </a>
        <div class="count-controls">
          <div class="controls">
            <button type="button" class="minus" data-product_id="<?php echo esc_attr( $_product->get_id() ); ?>"></button>
            <input
              class="count"
              type="text"
              min="<?php echo $product_min_qty ?>"
              max="<?php echo $product_max_qty ?>"
              value="<?php echo $product_quantity; ?>"
              name="basket-item-count"
              readonly
            >
            <button type="button" class="plus" data-product_id="<?php echo esc_attr( $_product->get_id() ); ?>">
              <?php if ( $product_max_qty ): ?>
                <span class="popup-max-count">This item is limited to <?php echo $product_max_qty ?> per customer.</span>
              <?php endif; ?>
            </button>
          </div>
        </div>
      </div>
      <div class="product-total">
        <?php
          echo apply_filters(
            'woocommerce_cart_item_remove_link',
            sprintf(
              '<a href="%s" class="remove" aria-label="%s" data-product_id="%s" data-product_sku="%s"></a>',
              esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
              esc_html__( 'Remove this item', 'woocommerce' ),
              esc_attr( $_product->get_id() ),
              esc_attr( $_product->get_sku() )
            ),
            $cart_item_key
          );
        ?>
        <div class="product-total-wrapper-price">
          <?php if ( $_product->is_on_sale() ): ?>
            <?php
              $regular_price = $_product->get_regular_price();
              if ( $product_quantity > 1 ) {
                $regular_price = $regular_price * $product_quantity;
              }
             ?>
            <del class="product-total-wrapper-price__regular"><?php echo get_woocommerce_currency_symbol().$regular_price; ?></del>
          <?php endif; ?>
          <?php echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
        </div>
      </div>
    </div>
    <?php
  }
}

do_action( 'woocommerce_review_order_after_cart_contents' );
?>
</div>
