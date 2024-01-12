<?php

use \Routiz\Inc\Src\Request\Request;

defined('ABSPATH') || exit;

$is_business = get_user_meta( get_current_user_id(), 'rz_role', true ) == 'business';
$entries = \Routiz\Inc\Src\Woocommerce\Account\Account::get_entries( ( isset( $_GET['type'] ) or ! $is_business ) ? 'outgoing' : 'ingoing' );
$request = Request::instance();
$page = $request->has('onpage') ? $request->get('onpage') : 1;

?>

<?php if( $is_business ): ?>

    <div class="rz-boxes-tabs">
        <ul>
            <li class="<?php if( ! isset( $_GET['type'] ) ) { echo 'rz--active'; } ?>">
                <a href="<?php echo esc_url( wc_get_account_endpoint_url( 'entries' ) ); ?>">
                    <?php esc_html_e( 'Incoming', 'routiz' ); ?>
                </a>
            </li>
            <li class="<?php if( isset( $_GET['type'] ) ) { echo 'rz--active'; } ?>">
                <a href="<?php echo esc_url( add_query_arg([ 'type' => 'outgoing' ], wc_get_account_endpoint_url( 'entries' ) ) ); ?>">
                    <?php esc_html_e( 'Outgoing', 'routiz' ); ?>
                </a>
            </li>
        </ul>
    </div>

    <?php if( ! isset( $_GET['type'] ) ): ?>
        <p><?php esc_html_e( 'Entries that you have recieved from other users', 'routiz' ); ?></p>
    <?php else: ?>
        <p><?php esc_html_e( 'Entries that you have sent to other users', 'routiz' ); ?></p>
    <?php endif; ?>

<?php endif; ?>

<?php if( $entries->have_posts() ): ?>
    <div class="rz-boxes">
        <?php while( $entries->have_posts() ) : $entries->the_post(); ?>
            <?php Rz()->the_template('routiz/account/entries/row'); ?>
        <?php endwhile; wp_reset_postdata(); ?>
    </div>
<?php else: ?>
    <p><strong><?php esc_html_e( 'No entries were found', 'routiz' ); ?></strong></p>
<?php endif; ?>

<div class="rz-paging">
    <?php
        echo Rz()->pagination([
            'base' => add_query_arg( [ 'onpage' => '%#%' ], wc_get_account_endpoint_url( 'entries' ) ),
            'format' => '?onpage=%#%',
            'current' => $page,
            'total' => $entries->max_num_pages,
        ]);
    ?>
</div>

<?php Rz()->the_template( 'routiz/account/entries/modal/modal' ); ?>
<?php Rz()->the_template( 'routiz/single/modal/submit-review' ); ?>
<?php // Rz()->the_template( 'routiz/account/entries/modal/action/modal' ); ?>
