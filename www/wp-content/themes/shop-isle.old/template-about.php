<?php
/**
 * The template for displaying about us page.
 *
 * Template Name: About us page
 *
 */

get_header(); ?>
	<!-- Wrapper start -->
	<div class="main">
	
		<!-- Header section start -->
		
		<?php
			$shop_isle_header_image = get_header_image();
			if( !empty($shop_isle_header_image) ) {
				echo '<section class="page-header-module module bg-dark" data-background="'.esc_url( $shop_isle_header_image ).'">';
			} else {
				echo '<section class="page-header-module module bg-dark">';
			}
		?>
				<div class="container">

						<div class="row">

							<div class="col-sm-10 col-sm-offset-1">

								<h1 class="module-title font-alt"><?php the_title(); ?></h1>

								<?php

								/* Header description */

								$shop_isle_shop_id = get_the_ID();

								if( !empty($shop_isle_shop_id) ) {

									$shop_isle_page_description = get_post_meta($shop_isle_shop_id, 'shop_isle_page_description');

									if( !empty($shop_isle_page_description[0]) ) {
										echo '<div class="module-subtitle font-serif mb-0">'.$shop_isle_page_description[0].'</div>';
									}

								}
								?>

							</div>

						</div><!-- .row -->

					</div><!-- .container -->
				</section><!-- .page-header-module -->
				<!-- Header section end -->
				
				<!-- About start -->
				<?php
				
					if ( have_posts() ) { 
						while ( have_posts() ) { 
							
							the_post();
					
							$shop_isle_content_aboutus = get_the_content();
						
						}
					}	
					
					if( trim($shop_isle_content_aboutus) != "" ) {
						
						echo '<section class="module">';
						
							echo '<div class="container">';

								echo '<div class="row">';

									echo '<div class="col-sm-12">';

										the_content();

									echo '</div>';

								echo '</div>';

							echo '</div>';
							
						echo '</section>';
						
					}
				?>
				
				<!-- About end -->
				
				<!-- Divider -->
				<!--hr class="divider-w"-->
				<!-- Divider -->

		<!-- Features start -->
		<!--section class="module module-advantages">
			<div class="container">

				<?php
				
				$shop_isle_our_advantages_title = get_theme_mod('shop_isle_our_advantages_title',__( 'Our advantages', 'shop-isle' ));
				if( !empty($shop_isle_our_advantages_title) ):
					echo '<div class="row">';
						echo '<div class="col-sm-6 col-sm-offset-3">';
							echo '<h2 class="module-title font-alt our_advantages">'.$shop_isle_our_advantages_title.'</h2>';
						echo '</div>';
					echo '</div>';	
				endif;	
				
				$shop_isle_advantages = get_theme_mod('shop_isle_advantages',json_encode(array( array('icon_value' => 'icon_lightbulb' , 'text' => __('Ideas and concepts','shop-isle'), 'subtext' => __('Lorem ipsum dolor sit amet, consectetur adipiscing elit.','shop-isle')), array('icon_value' => 'icon_tools' , 'text' => __('Designs & interfaces','shop-isle'), 'subtext' => __('Lorem ipsum dolor sit amet, consectetur adipiscing elit.','shop-isle')), array('icon_value' => 'icon_cogs' , 'text' => __('Highly customizable','shop-isle'), 'subtext' => __('Lorem ipsum dolor sit amet, consectetur adipiscing elit.','shop-isle')), array('icon_value' => 'icon_like', 'text' => __('Easy to use','shop-isle'), 'subtext' => __('Lorem ipsum dolor sit amet, consectetur adipiscing elit.','shop-isle')))));
					
				if( !empty( $shop_isle_advantages ) ):
									
					$shop_isle_advantages_decoded = json_decode($shop_isle_advantages);
									
					if( !empty($shop_isle_advantages_decoded) ):
										
						echo '<div class="row multi-columns-row">';
									
							foreach($shop_isle_advantages_decoded as $shop_isle_advantage):
											
								echo '<div class="col-sm-6 col-md-3 col-lg-3">';

									echo '<div class="features-item">';
									
									
										if( !empty($shop_isle_advantage->icon_value) ):
											echo '<div class="features-icon">';
											
												if (function_exists ( 'icl_t' ) && !empty($shop_isle_advantage->id)){
													$shop_isle_advantage_icon_value = icl_t( 'Advantage '.$shop_isle_advantage->id, 'Advantage icon',$shop_isle_advantage->icon_value );
													echo '<span class="'.esc_attr( $shop_isle_advantage_icon_value ).'"></span>';
												} else {
													echo '<span class="'.esc_attr( $shop_isle_advantage->icon_value ).'"></span>';
												}
												
											echo '</div>';
										endif;	

										if( !empty($shop_isle_advantage->text) ):
										
											if (function_exists ( 'icl_t' ) && !empty($shop_isle_advantage->id)){
												$shop_isle_advantage_text = icl_t( 'Advantage '.$shop_isle_advantage->id, 'Advantage text', $shop_isle_advantage->text );
												echo '<h3 class="features-title font-alt">'.$shop_isle_advantage_text.'</h3>';	
											} else {
												echo '<h3 class="features-title font-alt">'.$shop_isle_advantage->text.'</h3>';
											}
										endif;	

										if( !empty($shop_isle_advantage->subtext) ):
											if (function_exists ( 'icl_t' ) && !empty($shop_isle_advantage->id)){
												$shop_isle_advantage_subtext = icl_t( 'Advantage '.$shop_isle_advantage->id ,'Advantage subtext', $shop_isle_advantage->subtext );
												echo $shop_isle_advantage_subtext;
											} else {
												echo $shop_isle_advantage->subtext;
											}	
										endif;	
									echo '</div>';

								echo '</div>';
					
							endforeach;
									
						echo '</div>';
									
					endif;
							
				endif;
				
				?>

			</div><!-- .container -->
		</section-->
		<!-- Features end -->
	
<?php get_footer(); ?>