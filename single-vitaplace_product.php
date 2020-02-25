<?php
if ( !defined('ABSPATH') ){ die(); }

    require_once (__DIR__ . '/src/util/category_helper.php');

    global $avia_config;

    $vitaOptions = get_option('vitaplace');
    $countryMode = $vitaOptions['app_country_mode'];
    $competenceCenterMode = $countryMode === 'CH_CC';
    $chMode = in_array($countryMode, ['CH', 'CH_CC']);

    $postId = get_the_ID();

    $shopUrl = get_post_meta($postId, 'vtp_shop_url', true);
    $imageUrl = get_post_meta($postId, 'vtp_image_url', true);

    /*
     * get_header is a basic wordpress function, used to retrieve the header.php file in your theme directory.
     */
    get_header();


    if( get_post_meta(get_the_ID(), 'header', true) != 'no') echo avia_title();

    do_action( 'ava_after_main_title' );
?>

<?php if($competenceCenterMode): ?>
    <div class="vtp_banner">
        <div class="container">
            <a class="btn btn-default pull-right" href="<?= $shopUrl ?>">zurück</a>
            <span class="vtp_banner__text">Sie befinden sich auf der schweizer Seite info.vitaplace.ch</span>
        </div>
    </div>
<?php endif; ?>

    <div class="container_wrap container_wrap_first main_color <?php avia_layout_class( 'main' ); ?>">

        <div class="container">

            <main class="template-page template-portfolio content  <?php avia_layout_class( 'content' ); ?> units">

                <?php
                // check if we got posts to display:
                if (have_posts()) :
                    while (have_posts()) : the_post();
                        $postId = get_the_ID();
                        $chHost = $vitaOptions['app_ch_host'] ? $vitaOptions['app_ch_host'] : 'https://vitaplace.ch';
                        $deHost = $vitaOptions['app_de_host'] ? $vitaOptions['app_de_host'] : 'https://vitaplace.de';

                        $referer = get_parameter('referer');

                        $registeredInCH = get_post_meta($postId, 'vtp_visibility_ch', true);

                        $vtpAttributes = [
                            'vtp_supplier' => 'Anbieter',
                            // 'vtp_dosage' => 'Darreichungsform', // the data does not give this value
                            'vtp_package_size' => 'Packungsgröße',
                            'vtp_pzn' => 'PZN'
                        ]


                        ?>

                        <article class='post-entry post-entry-type-page post-vitaplace-product' <?php avia_markup_helper(array('context' => 'entry')); ?>>

                            <div class="entry-content-wrapper clearfix">
                                <div class="post-vitaplace-product__wrapper">

                                    <h1 class="vtp-title">Information: <?= get_the_title() ?></h1>

                                    <div class="vtp-markup">
                                        <?php if ($registeredInCH): ?>
                                            <div class="vtp-indikation">
                                                <?php if ($competenceCenterMode) : ?>
                                                    <div>
                                                        <?= get_post_meta($postId, 'vtp_indikation', true); ?>
                                                    </div>
                                                <?php elseif ($chMode): ?>
                                                    <h2>Indikationen</h2>
                                                    <div>
                                                        <?= get_post_meta($postId, 'vtp_indikation', true); ?>
                                                    </div>
                                                    <?php if ($referer === 'vp_de'): ?>
                                                        <a href="<?= get_post_meta($postId, 'vtp_shop_url', true) ?>">&lt; Zurück zum Shop Vitaplace Deutschland</a>
                                                    <?php elseif($referer === 'vp_de_m'): ?>
                                                        <a href="<?= $deHost ?>/produkte/<?= get_post_meta($postId, 'vtp_pzn', true) ?>/">&gt; Zurück zu unserer deutschen Seite</a>
                                                    <?php endif; ?>
                                                <?php else : ?>
                                                    <h2>Anwendnung</h2>
                                                    <a href="<?= $chHost ?>/produkte/<?= get_post_meta($postId, 'vtp_pzn', true) ?>/?referer=vp_de_m">&gt; Mehr Info auf unserer Schweizer Seite</a>
                                                <?php endif; ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                    <?php if($competenceCenterMode): ?>
                                        <a class="btn btn-default pull-right" href="<?= $shopUrl ?>">zurück</a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </article><!--end post-entry-->
                    <?php
                    endwhile;
                else:
                    ?>
                    <article class="entry">
                        <header class="entry-content-header">
                            <h1 class='post-title entry-title'><?php _e('Nothing Found', 'avia_framework'); ?></h1>
                        </header>

                        <?php get_template_part('includes/error404'); ?>

                        <footer class="entry-footer"></footer>
                    </article>
                <?php
                endif;
                ?>

                <!--end content-->
            </main>

            <?php

            //get the sidebar
            $avia_config['currently_viewing'] = 'page';
            get_sidebar();

            ?>

        </div><!--end container-->

    </div><!-- close default .container_wrap element -->



<?php get_footer(); ?>