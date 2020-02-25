<?php
	if ( !defined('ABSPATH') ){ die(); }

	require_once (__DIR__ . '/src/util/category_helper.php');
	
	global $avia_config;

	/*
	 * get_header is a basic wordpress function, used to retrieve the header.php file in your theme directory.
	 */
	 get_header();


 	 if( get_post_meta(get_the_ID(), 'header', true) != 'no') echo avia_title();
 	 
 	 do_action( 'ava_after_main_title' );
	 ?>

		<div class="container_wrap container_wrap_first main_color <?php avia_layout_class( 'main' ); ?>">

			<div class="container">

				<main class="template-page template-portfolio content  <?php avia_layout_class( 'content' ); ?> units">

                    <?php
                        // check if we got posts to display:
                        if (have_posts()) :
                            while (have_posts()) : the_post();
                                $postId = get_the_ID();
                                
                                $vitaOptions = get_option('vitaplace');
								$chMode = $vitaOptions['app_country_mode'] === 'CH';
								$chHost = $vitaOptions['app_ch_host'] ? $vitaOptions['app_ch_host'] : 'https://vitaplace.ch';
								$deHost = $vitaOptions['app_de_host'] ? $vitaOptions['app_de_host'] : 'https://vitaplace.de';
                                
                                $referer = get_parameter('referer');
                                
                                $registeredInCH = get_post_meta($postId, 'vtp_visibility_ch', true);
                                $shopUrl = get_post_meta($postId, 'vtp_shop_url', true);
                                $imageUrl = get_post_meta($postId, 'vtp_image_url', true);

                                $vtpAttributes = [
                                    'vtp_supplier' => 'Anbieter',
                                    // 'vtp_dosage' => 'Darreichungsform', // the data does not give this value
                                    'vtp_package_size' => 'Packungsgröße',
                                    'vtp_pzn' => 'PZN'
                                ]


                    ?>

                            <article class='post-entry post-entry-type-page post-vitaplace-product' <?php avia_markup_helper(array('context' => 'entry')); ?>>

                                <div class="entry-content-wrapper clearfix">

                                    <?php if (array_key_exists('vitaplace_products', $GLOBALS)): ?>
                                    <div class="vtp-categories">
                                        <?php
                                            $cats = get_the_terms($postId, 'vitaplace_categories');
                                            $cat = $cats && count($cats) > 0 ? $cats[0] : null;
                                            $rootCat = $cat ? get_root_category($cat) : null;
                                        ?>

                                        <h2 class="vtp-categories__head">Naturheilkunde</h2>

                                        <?php echo $GLOBALS['vitaplace_products']::build_vtp_category_tree($cat ? $cat->term_id : null, $rootCat ? $rootCat->term_id : null) ?>

                                    </div>
                                    <?php endif; ?>

                                    <div class="post-vitaplace-product__wrapper">
                                        <?php if ($chMode && !$registeredInCH): ?>
                                            <div class="message message-warning">
                                                Dieses Produkt ist noch nicht in der Schweiz registriert und darf im Moment nur in Deutschland verkauft werden.
                                                Sie finden es in unserem <a href="<?= $shopUrl ?>">deutschen Online Shop</a>.
                                            </div>
                                        <?php endif; ?>

                                        <div class="vtp-header">
                                            <div class="vtp-image-wrapper">
                                                <?php if ($imageUrl): ?>
                                                    <img class="vtp-image" src="<?= $imageUrl ?>"/>
                                                <?php endif; ?>
                                            </div>

                                            <div class="vtp-product-information">
                                                <h1 class="vtp-title"><?php if(!$chMode): ?><?= get_post_meta($postId, 'vtp_price', true) ?> €<br/><?php endif; ?><?= get_the_title() ?></h1>

                                                <div class="vtp-attributes">
                                                    <?php foreach ($vtpAttributes as $attr => $label): ?>
                                                        <?php
                                                            $value = get_post_meta($postId, $attr, true);

                                                            if ($attr === 'vtp_package_size') {
                                                                $value .= get_post_meta($postId, 'vtp_dosage', true);
                                                            }

                                                        ?>
                                                        <?php if ($value): ?>
                                                            <div class="vtp-attributes--line">
                                                                <div class="vtp-attributes--label">
                                                                    <?= $label ?>:
                                                                </div>
                                                                <div class="vtp-attributes--value">
                                                                    <?= $value ?>
                                                                </div>
                                                            </div>
                                                        <?php endif; ?>
                                                    <?php endforeach; ?>
                                                    
                                                    <?php if ($chMode): ?>
                                                    	<strong><?= $vitaOptions['app_order_help_text'] ?>  <?= $vitaOptions['app_ch_service_phone'] ?> </strong>
                                                    <?php else: ?>
                                                    	<a class="btn btn-default" href="<?= get_post_meta($postId, 'vtp_shop_url', true) ?>">im Shop kaufen</a>
                                                    <?php endif; ?>
                                                </div>

                                            </div>
                                        </div>
            
                                        <div class="vtp-markup">
        	                                <?php if ($registeredInCH): ?>
		                                        <div class="vtp-indikation">
	    	                                    	<?php if ($chMode): ?>
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
                                        	
                                            <?= get_post_meta($postId, 'vtp_details')[0] ?>
                                        </div>

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