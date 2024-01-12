<?php

namespace Routiz\Inc\Src\Admin;

use \Routiz\Inc\Src\Request\Request;
use \Routiz\Inc\Src\Wallet;

class Settings {

    use \Routiz\Inc\Src\Traits\Singleton;

    function __construct() {

        // add settings page to rz_listing
        add_action( 'admin_menu' , [ $this, 'add_submenu_page' ] );

        // update settings
        add_action( 'routiz/admin/update_settings', [ $this, 'update_settings' ] );

        // update payouts
        add_action( 'routiz/admin/update_payouts', [ $this, 'update_payouts' ] );

        // update earnings
        add_action( 'routiz/admin/update_earnings', [ $this, 'update_earnings' ] );

    }

    public function enqueue_earnings_scripts() {
        Panel::instance();
    }

    public function enqueue_menu_scripts() {
        Panel::instance();
    }

    public function add_submenu_page() {

        add_submenu_page(
            'edit.php?post_type=rz_listing_type', // parent slug
            esc_html__('Payouts', 'routiz'), // page title
            esc_html__('Payouts', 'routiz'), // menu title
            'manage_options', // capability
            'rz_payouts', // menu slug
            [ $this, 'payouts_page_output' ]
        );

        add_action( sprintf( 'load-%s',
            add_users_page(
                esc_html__('Earnings', 'routiz'), // page title
                esc_html__('Earnings', 'routiz'), // menu title
                'manage_options', // capability
                'rz_earnings', // menu slug
                [ $this, 'earnings_page_output' ]
            )),
            [ $this, 'enqueue_earnings_scripts' ]
        );

        add_action( sprintf( 'load-%s',
            add_submenu_page(
                'edit.php?post_type=rz_listing_type', // parent slug
                esc_html__('Settings', 'routiz'), // page title
                esc_html__('Settings', 'routiz'), // menu title
                'manage_options', // capability
                'rz_settings', // menu slug
                [ $this, 'settings_page_output' ]
            )),
            [ $this, 'enqueue_menu_scripts' ]
        );

    }

    public function settings_page_output() {

        if( isset( $_POST ) and ! empty( $_POST ) ) {
            do_action('routiz/admin/update_settings');
        }

        Rz()->the_template('admin/settings/settings');

    }

    public function payouts_page_output() {

        do_action('routiz/admin/update_payouts');

        Rz()->the_template('admin/payouts/payouts');

    }

    public function earnings_page_output() {

        // Panel::instance();

        do_action('routiz/admin/update_earnings');

        Rz()->the_template('admin/earnings/earnings');

    }

    public function update_settings() {

        if( isset( $_POST ) and ! empty( $_POST ) ) {

            foreach( $_POST as $id => $value ) {
                if( substr( $id, 0, 3 ) == 'rz_' ) {
                    update_option(
                        $id,
                        $value
                    );
                }
            }
        }
    }

    public function update_payouts() {

        if( ! current_user_can( 'manage_options' ) ) {
            return;
        }

        $request = Request::instance();

        if( $request->has('action') ) {

            $payout_id = (int) $request->get('id');

            switch( $request->get('action') ) {
                case 'approve':
                    Wallet::approve_payout( $payout_id );
                    break;
                case 'decline':
                    Wallet::decline_payout( $payout_id );
                    break;
            }
        }
    }

    public function update_earnings() {

        if( ! current_user_can( 'manage_options' ) ) {
            return;
        }

        $request = Request::instance();

        // action delete
        if( $request->get('action') == 'delete' ) {
            $transaction_id = $request->get('id');
            if( current_user_can( 'manage_options' ) ) {
                if( wp_verify_nonce( $request->get('_wpnonce'), "routiz_transaction{$transaction_id}" ) ) {

                    global $wpdb;

                    // get transaction
                    $transaction = $wpdb->get_row("
                        SELECT *
                        FROM {$wpdb->prefix}routiz_wallet_transactions
                        WHERE id = {$transaction_id}
                        LIMIT 1
                    ");

                    if( $transaction->id ) {
                        if( floatval( $transaction->amount ) > 0 ) {

                            $user = get_user_by( 'email', $request->get('user_email') );

                            if( isset( $user->ID ) ) {

                                $wallet = new Wallet( $user->ID );

                                $amount = floatval( $transaction->amount ) * ( $transaction->type == 'credit' ? -1 : 1 );
                                $wallet->add_to_balance( $amount );

                            }
                        }
                    }

                    // delete transaction
                    $wpdb->get_results("
                        DELETE
                        FROM {$wpdb->prefix}routiz_wallet_transactions
                        WHERE id = {$transaction_id}
                    ");

                }
            }
        }

        // add transaction
        if( isset( $_POST ) and ! empty( $_POST ) ) {

            $success = false;
            $message = '';

            $user = get_user_by( 'email', $request->get('user_email') );

            if( in_array( $request->get('type'), [ 'credit', 'debit' ] ) ) {

                if( isset( $user->ID ) ) {

                    $amount = floatval( $request->get('amount') );

                    if( $amount > 0 ) {

                        $amount = floatval( $amount * ( $request->get('type') == 'credit' ? 1 : -1 ) );
                        $wallet = new Wallet( $user->ID );
                        $wallet->add_funds( $amount, null, 'adjustment' );

                        $success = true;
                        $message = esc_html__( 'The transaction was added successfully', 'routiz' );

                    }else{

                        $message = esc_html__( 'Please add amount', 'routiz' );

                    }

                }

            }else{

                $message = esc_html__( 'Please select transaction type', 'routiz' );

            }

            $args = [
                'page' => 'rz_earnings',
                'user_email' => $request->get('user_email'),
                'success' => $success ? 'yes' : 'no',
                'msg' => $message,
            ];

            if( ! $success ) {
                $args['amount'] = $request->get('amount');
                $args['type'] = $request->get('type');
            }

            wp_redirect( add_query_arg( $args, admin_url('users.php') ) );
            exit;

        }

    }
}
