<?php

defined('ABSPATH') || exit;

$meta = Rz()->get_meta( 'rz_custom_icon_set', get_the_ID() );
$has_icons = isset( $meta['icons'] ) and ! empty( $meta['icons'] ) and is_array( $meta['icons'] );
$post_status = get_post_status();

?>

<div class="rz-outer">
    <div class="rz-panel rz-ml-auto rz-mr-auto">
        <div class="rz-content">
            <section class="rz-sections">
                <aside class="rz-section">

                    <div class="rz-upload-inline <?php if( $has_icons ) { echo 'rz-none'; } ?>">

                        <label class="rz--label rz-none" for="icon-package"></label>
                        <input type="file" id="icon-package" name="icon-package" class="rz-none rz--file"
                            accept="zip,application/zip,application/x-zip,application/x-zip-compressed,application/zip-compressed,application/easykaraoke.cdgdownload">

                        <div class="upload-cover-drag">
                            <div class="rz--inner">

                                <?php if( $post_status !== 'publish' ): ?>
                                    <div class="rz--icon">
                                        <i class="fas fa-save"></i>
                                    </div>
                                    <div class="rz--content">
                                        <p><strong><?php esc_html_e( 'Name and publish the post, then upload your font package', 'routiz' ); ?></strong></p>
                                    </div>
                                <?php else: ?>
                                    <div class="rz--icon">
                                        <i class="fas fa-upload"></i>
                                    </div>
                                    <div class="rz--content">
                                        <p><strong><?php echo sprintf( esc_html__( 'Upload your icon package generated by the %s', 'routiz' ), '<a href="https://icomoon.io/app/" target="_blank">' . esc_html__('Icomoon App', 'routiz') . '</a>' ); ?></strong></p>
                                    </div>
                                    <div class="rz--upload">
                                        <div class="rz--button">
                                            <a href="#" class="rz-button">
                                                <span><?php esc_html_e( 'Select icon package', 'routiz' ); ?></span>
                                                <?php Rz()->preloader() ?>
                                            </a>
                                        </div>
                                        <div class="rz--name">
                                            <div class="rz-form-group">
                                                <input type="text" name="icon-package-name" value="" disabled>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="upload-thumbs">
                            <ul>
                                <li>
                                    <span class="upload-background"><a href="#" class="upload-remove"><i class="icon icon-close-circle-bold"></i></a></span>
                                    <i class="icon icon-image"></i>
                                </li>
                                <li>
                                    <span class="upload-background"><a href="#" class="upload-remove"><i class="icon icon-close-circle-bold"></i></a></span>
                                    <i class="icon icon-image"></i>
                                </li>
                                <li>
                                    <span class="upload-background"><a href="#" class="upload-remove"><i class="icon icon-close-circle-bold"></i></a></span>
                                    <i class="icon icon-image"></i>
                                </li>
                                <li>
                                    <span class="upload-background"><a href="#" class="upload-remove"><i class="icon icon-close-circle-bold"></i></a></span>
                                    <i class="icon icon-image"></i>
                                </li>
                                <li>
                                    <span class="upload-background"><a href="#" class="upload-remove"><i class="icon icon-close-circle-bold"></i></a></span>
                                    <i class="icon icon-image"></i>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <?php if( $has_icons ): ?>
                        <div class="rz-icons-inline">
                            <p class="">
                                <a href="#" class="rz-button rz--small" data-action="re-upload-set">
                                    <span><?php esc_html_e( 'Re-upload icon set', 'routiz' ); ?></span>
                                </a>
                            </p>
                            <table class="rz-table rz-mt-4 rz-mb-4">
                                <thead>
                                    <tr>
                                        <th><?php esc_html_e( 'Package details', 'routiz' ); ?></th>
                                        <th>&nbsp;</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><?php esc_html_e( 'Icons', 'routiz' ); ?></td>
                                        <td><?php echo count( $meta['icons'] ); ?></td>
                                    </tr>
                                    <tr>
                                        <td><?php esc_html_e( 'Prefix', 'routiz' ); ?></td>
                                        <td><?php echo esc_attr( $meta['prefix'] ); ?></td>
                                    </tr>
                                </tbody>
                            </table>
                            <ul>
                                <?php foreach( $meta['icons'] as $key => $icon ): ?>
                                    <li title="<?php echo esc_attr( $icon ); ?>"><i class="<?php echo esc_attr( $meta['prefix'] . ' ' . $meta['prefix'] . $icon ); ?>"></i></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                </aside>
            </section>
        </div>
    </div>
</div>
