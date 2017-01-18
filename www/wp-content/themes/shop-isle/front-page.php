<?php
if ( 'posts' == get_option( 'show_on_front' ) ) {

	get_header();

	/* Wrapper start */
	echo '<div class="main">';

	/******************************/
	/********    SLIDER   *********/
	/******************************/
	$shop_isle_slider_hide = get_theme_mod('shop_isle_slider_hide');
	$shop_isle_homepage_slider_shortcode = get_theme_mod('shop_isle_homepage_slider_shortcode');

	if ( isset($shop_isle_slider_hide) && $shop_isle_slider_hide != 1 ) {
		echo '<section id="home" class="home-section home-parallax home-fade'. (empty($shop_isle_homepage_slider_shortcode) ? ' home-full-height' : ' home-slider-plugin' ) .'">';
	} elseif ( is_customize_preview() ) {
		echo '<section id="home" class="home-section home-parallax home-fade'. (empty($shop_isle_homepage_slider_shortcode) ? ' home-full-height' : ' home-slider-plugin' ) .' shop_isle_hidden_if_not_customizer">';
	}

	if( ( isset($shop_isle_slider_hide) && $shop_isle_slider_hide != 1 ) || is_customize_preview() ) {

		if( !empty($shop_isle_homepage_slider_shortcode) ) {

			echo do_shortcode( $shop_isle_homepage_slider_shortcode );

		} else {

			$shop_isle_slider = get_theme_mod( 'shop_isle_slider', json_encode( array(
				array(
					'image_url' => get_template_directory_uri() . '/assets/images/slide1.jpg',
					'link'      => '#',
					'text'      => __( 'ShopIsle', 'shop-isle' ),
					'subtext'   => __( 'WooCommerce Theme', 'shop-isle' ),
					'label'     => __( 'FIND OUT MORE', 'shop-isle' )
				),
				array(
					'image_url' => get_template_directory_uri() . '/assets/images/slide2.jpg',
					'link'      => '#',
					'text'      => __( 'ShopIsle', 'shop-isle' ),
					'subtext'   => __( 'Hight quality store', 'shop-isle' ),
					'label'     => __( 'FIND OUT MORE', 'shop-isle' )
				),
				array(
					'image_url' => get_template_directory_uri() . '/assets/images/slide3.jpg',
					'link'      => '#',
					'text'      => __( 'ShopIsle', 'shop-isle' ),
					'subtext'   => __( 'Responsive Theme', 'shop-isle' ),
					'label'     => __( 'FIND OUT MORE', 'shop-isle' )
				)
			) ) );

			if ( ! empty( $shop_isle_slider ) ) {

				$shop_isle_slider_decoded = json_decode( $shop_isle_slider );

				if ( ! empty( $shop_isle_slider_decoded ) ) {

					echo '<div class="hero-slider">';

					echo '<ul class="slides">';

					foreach ( $shop_isle_slider_decoded as $shop_isle_slide ) {

						if ( ! empty( $shop_isle_slide->image_url ) ) {

							if ( function_exists( 'icl_t' ) && ! empty( $shop_isle_slide->id ) ) {
								$shop_isle_slider_image_url = icl_t( 'Slide ' . $shop_isle_slide->id, 'Slide image', $shop_isle_slide->image_url );
								echo '<li class="bg-dark" style="background-image:url(' . esc_url( $shop_isle_slider_image_url ) . ')">';
							} else {
								echo '<li class="bg-dark" style="background-image:url(' . esc_url( $shop_isle_slide->image_url ) . ')">';
							}
							echo '<div class="home-slider-overlay"></div>';
							echo '<div class="hs-caption">';
							echo '<div class="caption-content">';

							if ( ! empty( $shop_isle_slide->text ) ) {
								if ( function_exists( 'icl_t' ) && ! empty( $shop_isle_slide->id ) ) {
									$shop_isle_slider_text = icl_t( 'Slide ' . $shop_isle_slide->id, 'Slide text', $shop_isle_slide->text );
									echo '<div class="hs-title-size-4 font-alt mb-30">' . $shop_isle_slider_text . '</div>';
								} else {
									echo '<div class="hs-title-size-4 font-alt mb-30">' . $shop_isle_slide->text . '</div>';
								}
							}

							if ( ! empty( $shop_isle_slide->subtext ) ) {
								if ( function_exists( 'icl_t' ) && ! empty( $shop_isle_slide->id ) ) {
									$shop_isle_slider_subtext = icl_t( 'Slide ' . $shop_isle_slide->id, 'Slide subtext', $shop_isle_slide->subtext );
									echo '<div class="hs-title-size-1 font-alt mb-40">' . $shop_isle_slider_subtext . '</div>';
								} else {
									echo '<div class="hs-title-size-1 font-alt mb-40">' . $shop_isle_slide->subtext . '</div>';
								}
							}

							if ( ! empty( $shop_isle_slide->link ) && ! empty( $shop_isle_slide->label ) ) {
								if ( function_exists( 'icl_t' ) && ! empty( $shop_isle_slide->id ) ) {
									$shop_isle_slider_link = icl_t( 'Slide ' . $shop_isle_slide->id, 'Slide button link', $shop_isle_slide->link );
									$shop_isle_slider_label = icl_t( 'Slide ' . $shop_isle_slide->id, 'Slide button label', $shop_isle_slide->label );
									echo '<a href="' . esc_url( $shop_isle_slider_link ) . '" class="section-scroll btn btn-border-w btn-round">' . $shop_isle_slider_label . '</a>';
								} else {
									echo '<a href="' . esc_url( $shop_isle_slide->link ) . '" class="section-scroll btn btn-border-w btn-round">' . $shop_isle_slide->label . '</a>';
								}
							}
							echo '</div><!-- .caption-content -->';
							echo '</div><!-- .hs-caption -->';
							echo '</li><!-- .bg-dark -->';

						}

					} /* end foreach */

					echo '</ul><!-- .slides -->';

					echo '</div><!-- .hero-slider -->';

				}
			}
		}

		echo '</section >';

	} /* END SLIDER */

	/* Wrapper start */

	$shop_isle_bg = get_theme_mod('background_color');

	if( isset($shop_isle_bg) && $shop_isle_bg!='' ) {

		echo '<div class="main front-page-main" style="background-color: #' . $shop_isle_bg . '">';

	} else {

		echo '<div class="main front-page-main" style="background-color: #FFF">';

	}

	if( defined('WCCM_VERISON') ) {

		/* Woocommerce compare list plugin */
		echo '<section class="module-small wccm-frontpage-compare-list">';
		echo '<div class="container">';
		do_action( 'shop_isle_wccm_compare_list' );
		echo '</div>';
		echo '</section>';

	}

	/***********************/
	/******  BANNERS *******/
	/***********************/

	$shop_isle_banners_hide = get_theme_mod('shop_isle_banners_hide');
	$shop_isle_banners_title = get_theme_mod('shop_isle_banners_title');

	if ( isset($shop_isle_banners_hide) && $shop_isle_banners_hide != 1 ) {
		echo '<section class="module-small home-banners">';
	} elseif ( is_customize_preview() ) {
		echo '<section class="module-small home-banners shop_isle_hidden_if_not_customizer">';
	}

	if( ( isset($shop_isle_banners_hide) && $shop_isle_banners_hide != 1) || is_customize_preview() ) {

		$shop_isle_banners = get_theme_mod('shop_isle_banners', json_encode(array( array('image_url' => get_template_directory_uri().'/assets/images/banner1.jpg' ,'link' => '#' ),array('image_url' => get_template_directory_uri().'/assets/images/banner2.jpg' ,'link' => '#'), array('image_url' => get_template_directory_uri().'/assets/images/banner3.jpg' ,'link' => '#') )));

		if( !empty( $shop_isle_banners ) ) {

			$shop_isle_banners_decoded = json_decode($shop_isle_banners);

			if( !empty($shop_isle_banners_decoded) ) {

				echo '<div class="container">';

				if ( ( isset($shop_isle_banners_title) && trim($shop_isle_banners_title) !== '' ) || is_customize_preview() ) {

					echo '<div class="row">';

					echo '<div class="col-sm-6 col-sm-offset-3">';

					echo '<h2 class="module-title font-alt product-banners-title'. ( is_customize_preview() && trim($shop_isle_banners_title)==='' ? ' shop_isle_hidden_if_not_customizer' : '' ) .'">'. $shop_isle_banners_title .'</h2>';

					echo '</div>';

					echo '</div>';

				}

				echo '<div class="row shop_isle_bannerss_section">';

				foreach($shop_isle_banners_decoded as $shop_isle_banner) {

					if ( !empty($shop_isle_banner->image_url) ) {

						echo '<div class="col-sm-4"><div class="content-box mt-0 mb-0"><div class="content-box-image">';

						if ( !empty($shop_isle_banner->link) ) {

							if (function_exists ( 'icl_t' ) && !empty($shop_isle_banner->id)){
								$shop_isle_banner_link = icl_t( 'Banner '.$shop_isle_banner->id, 'Banner link', $shop_isle_banner->link );
								$shop_isle_banner_image_url = icl_t( 'Banner '.$shop_isle_banner->id, 'Banner image', $shop_isle_banner->image_url );
								echo '<a href="' . esc_url( $shop_isle_banner_link ) . '"><img src="' . esc_url( $shop_isle_banner_image_url ) . '"></a>';
							} else {
								echo '<a href="' . esc_url( $shop_isle_banner->link ) . '"><img src="' . esc_url( $shop_isle_banner->image_url ) . '"></a>';
							}
						}
						else {
							if (function_exists ( 'icl_t' ) && !empty($shop_isle_banner->id)){
								$shop_isle_banner_image_url = icl_t( 'Banner '.$shop_isle_banner->id, 'Banner image', $shop_isle_banner->image_url );
								echo '<a><img src="' . esc_url( $shop_isle_banner_image_url ) . '"></a>';
							} else {
								echo '<a><img src="' . esc_url( $shop_isle_banner->image_url ) . '"></a>';
							}
						}
						echo '</div></div></div>';

					}

				}

				echo '</div><!-- .shop_isle_bannerss_section -->';

				echo '</div><!-- .container -->';

			}

		}

		echo '</section>';

		echo '<hr class="divider-w">';

	}	/* END BANNERS */

	/*********************************/
	/******* Latest products *********/
	/*********************************/

	$shop_isle_products_hide = get_theme_mod('shop_isle_products_hide');

	/* Latest products */

	if ( isset($shop_isle_products_hide) && $shop_isle_products_hide != 1 ) {
		echo '<section id="latest" class="module-small">';
	} elseif ( is_customize_preview() ) {
		echo '<section id="latest" class="module-small shop_isle_hidden_if_not_customizer">';
	}

	if( ( isset($shop_isle_products_hide) && $shop_isle_products_hide != 1 ) || is_customize_preview() ) {

		echo '<div class="container">';

		$shop_isle_products_title = get_theme_mod('shop_isle_products_title',__( 'Latest products', 'shop-isle' ));
		if( !empty($shop_isle_products_title) ) {
			echo '<div class="row">';
			echo '<div class="col-sm-6 col-sm-offset-3">';
			echo '<h2 class="module-title font-alt product-hide-title">'.$shop_isle_products_title.'</h2>';
			echo '</div>';
			echo '</div>';
		}

		$shop_isle_products_shortcode = get_theme_mod('shop_isle_products_shortcode');
		$shop_isle_products_category = get_theme_mod('shop_isle_products_category');

		/*********************************/
		/**** Woocommerce shortcode ******/
		/*********************************/

		if( isset($shop_isle_products_shortcode) && !empty($shop_isle_products_shortcode) ) {
			echo '<div class="products_shortcode">';
			echo do_shortcode($shop_isle_products_shortcode);
			echo '</div>';

			/**********************************/
			/***** Products from category *****/
			/**********************************/

		} elseif( isset($shop_isle_products_category) && !empty($shop_isle_products_category) && ($shop_isle_products_category != '-') ) {

			$shop_isle_latest_args = array(
				'post_type' => 'product',
				'posts_per_page' => 8,
				'orderby' =>'date',
				'order' => 'DESC',
				'tax_query' => array(
					array(
						'taxonomy'  => 'product_cat',
						'field'     => 'term_id',
						'terms'     => $shop_isle_products_category
					)),
				'meta_query' => array(
					array(
						'key' => '_visibility',
						'value' => 'hidden',
						'compare' => '!=',
					)),
				);

			$shop_isle_latest_loop = new WP_Query( $shop_isle_latest_args );

			if( $shop_isle_latest_loop->have_posts() ) {

				echo '<div class="row multi-columns-row">';

				while( $shop_isle_latest_loop->have_posts() ) {

					$shop_isle_latest_loop->the_post();
					global $product;

					echo '<div class="col-sm-6 col-md-3 col-lg-3">';
					echo '<div class="shop-item">';
					echo '<div class="shop-item-image">';

					if (has_post_thumbnail( $shop_isle_latest_loop->post->ID )) {
						echo get_the_post_thumbnail($shop_isle_latest_loop->post->ID, 'shop_catalog');
					} elseif( function_exists('woocommerce_placeholder_img_src') ) {
						echo '<img src="'.woocommerce_placeholder_img_src().'" alt="Placeholder" width="65px" height="115px" />';
					}

					echo '<div class="shop-item-detail">';
					if(!empty($product)) {
						echo do_shortcode( '[add_to_cart id="' . $shop_isle_latest_loop->post->ID . '"]' );
						if(function_exists('wccm_add_button')) {
							wccm_add_button();
						}
						if ( defined( 'YITH_WCQV' ) ) {

							echo '<a href="#" class="button yith-wcqv-button" data-product_id="' . esc_attr( get_the_ID() ) . '">'.__( 'Quick View','shop-isle' ).'</a>';

						}
					}
					echo '</div><!-- .shop-item-detail -->';
					echo '</div><!-- .shop-item-image -->';

					echo '<h4 class="shop-item-title font-alt"><a href="'.esc_url( get_permalink() ).'">'.get_the_title().'</a></h4>';

					$rating_html = '';
					if( function_exists( 'method_exists' ) && method_exists( $product, 'get_rating_html' ) && method_exists( $product, 'get_average_rating' ) ) {
						$shop_isle_avg = $product->get_average_rating();
						if( !empty($shop_isle_avg) ) {
							$rating_html = $product->get_rating_html( $shop_isle_avg );
						}
					}

					if ( !empty($rating_html) && get_option( 'woocommerce_enable_review_rating' ) === 'yes' ) {
						echo '<div class="product-rating-home">' . $rating_html . '</div>';
					}
					if( function_exists('method_exists') && method_exists( $product, 'get_display_price' ) ) {
						$shop_isle_price = $product->get_display_price();
					}
					if( function_exists('get_woocommerce_currency_symbol') && !empty($product) && !empty( $shop_isle_price ) ) {
						if( function_exists('get_woocommerce_price_format') ) {
							$format_string = get_woocommerce_price_format();
						}
						if( !empty($format_string) ) {
							switch ( $format_string ) {
								case '%1$s%2$s' :
									echo '<span class="shop-item-price"><span class="shop-item-currency">'.get_woocommerce_currency_symbol().'</span>'.$shop_isle_price.'</span>';
									break;
								case '%2$s%1$s' :
									echo '<span class="shop-item-price">'.$shop_isle_price.'<span class="shop-item-currency">'.get_woocommerce_currency_symbol().'</span></span>';
									break;
								case '%1$s&nbsp;%2$s' :
									echo '<span class="shop-item-price"><span class="shop-item-currency">'.get_woocommerce_currency_symbol().'</span> '.$shop_isle_price.'</span>';
									break;
								case '%2$s&nbsp;%1$s' :
									echo '<span class="shop-item-price">'.$shop_isle_price.' <span class="shop-item-currency">'.get_woocommerce_currency_symbol().'</span></span>';
									break;
							}
						} else {
							echo get_woocommerce_currency_symbol().$shop_isle_price;
						}
					}
					echo '</div><!-- .shop-item -->';
					echo '</div><!-- .col-sm-6 col-md-3 col-lg-3 -->';

				}

				echo '</div><!-- .row.multi-columns-row -->';

				echo '<div class="row mt-30">';
				echo '<div class="col-sm-12 align-center">';
				if( function_exists('woocommerce_get_page_id') ) {
					echo '<a href="'.esc_url( get_permalink( woocommerce_get_page_id( 'shop' )) ).'" class="btn btn-b btn-round">'.__('See all products','shop-isle').'</a>';
				}
				echo '</div>';
				echo '</div>';

			} else {

				echo '<div class="row">';
				echo '<div class="col-sm-6 col-sm-offset-3">';
				echo '<p class="">'.__('No products found.','shop-isle').'</p>';
				echo '</div>';
				echo '</div>';

			}

			wp_reset_postdata();


			/*****************************/
			/*****  Latest products ******/
			/*****************************/

		} else {

			$shop_isle_latest_args = array(
				'post_type' => 'product',
				'posts_per_page' => 8,
				'orderby' =>'date',
				'order' => 'DESC',
				'meta_query' => array(
					array(
						'key' => '_visibility',
						'value' => 'hidden',
						'compare' => '!=',
					)),
				);

			$shop_isle_latest_loop = new WP_Query( $shop_isle_latest_args );

			if( $shop_isle_latest_loop->have_posts() ) {

				echo '<div class="row multi-columns-row">';

				while( $shop_isle_latest_loop->have_posts() ) {

					$shop_isle_latest_loop->the_post();

					global $product;

					echo '<div class="col-sm-6 col-md-3 col-lg-3">';
					echo '<div class="shop-item">';
					echo '<div class="shop-item-image">';

					if (has_post_thumbnail( $shop_isle_latest_loop->post->ID )) {
						echo get_the_post_thumbnail($shop_isle_latest_loop->post->ID, 'shop_catalog');
					} elseif( function_exists('woocommerce_placeholder_img_src') ) {
						echo '<img src="'.woocommerce_placeholder_img_src().'" alt="Placeholder" width="65px" height="115px" />';
					}

					echo '<div class="shop-item-detail">';
					if( !empty($product) ) {
						echo do_shortcode( '[add_to_cart id="' . $shop_isle_latest_loop->post->ID . '"]' );
						if(function_exists('wccm_add_button')) {
							wccm_add_button();
						}
						if ( defined( 'YITH_WCQV' ) ) {

							echo '<a href="#" class="button yith-wcqv-button" data-product_id="' . esc_attr( get_the_ID() ) . '">'.__( 'Quick View','shop-isle' ).'</a>';

						}
					}
					echo '</div><!-- .shop-item-detail -->';
					echo '</div><!-- .shop-item-image -->';

					echo '<h4 class="shop-item-title font-alt"><a href="'.esc_url( get_permalink() ).'">'.get_the_title().'</a></h4>';

					$rating_html = '';
					if( function_exists( 'method_exists' ) && method_exists( $product, 'get_rating_html' ) && method_exists( $product, 'get_average_rating' ) ) {
						$shop_isle_avg = $product->get_average_rating();
						if( !empty($shop_isle_avg) ) {
							$rating_html = $product->get_rating_html( $shop_isle_avg );
						}
					}

					if ( !empty($rating_html) && get_option( 'woocommerce_enable_review_rating' ) === 'yes' ) {
						echo '<div class="product-rating-home">' . $rating_html . '</div>';
					}
					if( function_exists('method_exists') && method_exists( $product, 'get_display_price' ) ) {
						$shop_isle_price = $product->get_display_price();
					}
					if( function_exists('get_woocommerce_currency_symbol') && !empty($product) && !empty( $shop_isle_price ) ) {
						if( function_exists('get_woocommerce_price_format') ) {
							$format_string = get_woocommerce_price_format();
						}
						if( !empty($format_string) ) {
							switch ( $format_string ) {
								case '%1$s%2$s' :
									echo '<span class="shop-item-price"><span class="shop-item-currency">'.get_woocommerce_currency_symbol().'</span>'.$shop_isle_price.'</span>';
									break;
								case '%2$s%1$s' :
									echo '<span class="shop-item-price">'.$shop_isle_price.'<span class="shop-item-currency">'.get_woocommerce_currency_symbol().'</span></span>';
									break;
								case '%1$s&nbsp;%2$s' :
									echo '<span class="shop-item-price"><span class="shop-item-currency">'.get_woocommerce_currency_symbol().'</span> '.$shop_isle_price.'</span>';
									break;
								case '%2$s&nbsp;%1$s' :
									echo '<span class="shop-item-price">'.$shop_isle_price.' <span class="shop-item-currency">'.get_woocommerce_currency_symbol().'</span></span>';
									break;
							}
						} else {
							echo get_woocommerce_currency_symbol().$shop_isle_price;
						}
					}
					echo '</div>';
					echo '</div>';

				}

				echo '</div><!-- .row.multi-columns-row -->';

				echo '<div class="row mt-30">';
				echo '<div class="col-sm-12 align-center">';
				if( function_exists('woocommerce_get_page_id') ) {
					echo '<a href="'.esc_url( get_permalink( woocommerce_get_page_id( 'shop' )) ).'" class="btn btn-b btn-round">'.__('See all products','shop-isle').'</a>';
				}
				echo '</div>';
				echo '</div>';

			} else {

				echo '<div class="row">';
				echo '<div class="col-sm-6 col-sm-offset-3">';
				echo '<p class="">'.__('No products found.','shop-isle').'</p>';
				echo '</div>';
				echo '</div>';

			}

			wp_reset_postdata();

		}

		echo '</div><!-- .container -->';

		echo '</section><!-- #latest -->';

	} /* END Latest products */


	/**********************************/
	/*********    VIDEO **************/
	/*********************************/

	$shop_isle_video_hide = get_theme_mod('shop_isle_video_hide');
	$shop_isle_yt_link = get_theme_mod('shop_isle_yt_link');
	$shop_isle_yt_thumbnail = get_theme_mod('shop_isle_yt_thumbnail');

	if( empty($shop_isle_yt_thumbnail) ) {
		$shop_isle_do_video_thumbnail = preg_match( '/\/\/(www\.)?(youtu|youtube)\.(com|be)\/(watch|embed)?\/?(\?v=)?([a-zA-Z0-9\-\_]+)/', $shop_isle_yt_link, $shop_isle_youtube_matches );
		$shop_isle_youtube_id = ! empty( $shop_isle_youtube_matches ) ? $shop_isle_youtube_matches[6] : '';
		$shop_isle_yt_thumbnail = 'https://img.youtube.com/vi/' . $shop_isle_youtube_id . '/maxresdefault.jpg';
	}

	if( isset($shop_isle_video_hide) && $shop_isle_video_hide != 1 && !empty($shop_isle_yt_link) ) {
		echo '<section class="module module-video bg-dark-30">';
	} elseif ( !empty($shop_isle_yt_link) && is_customize_preview() ) {
		echo '<section class="module module-video bg-dark-30 shop_isle_hidden_if_not_customizer">';
	}

	if( ( isset($shop_isle_video_hide) && $shop_isle_video_hide != 1 && !empty($shop_isle_yt_link) ) || ( !empty($shop_isle_yt_link) && is_customize_preview() )  ) {

		echo '<div class="module-video-thumbnail"'. ( !empty( $shop_isle_yt_thumbnail ) ? ' style="background-image: url('.$shop_isle_yt_thumbnail.')' : '' ) .'"></div>';

		echo '<div>';
		$shop_isle_video_title = get_theme_mod('shop_isle_video_title');
		if( !empty($shop_isle_video_title) ):
			echo '<div class="container">';
			echo '<div class="row">';
			echo '<div class="col-sm-12">';
			echo '<h2 class="module-title font-alt mb-0 video-title">'.$shop_isle_video_title.'</h2>';
			echo '</div>';
			echo '</div>';
			echo '</div>';
		endif;
		?>
		<!-- Youtube player start-->
		<div class="video-player" data-property="{videoURL:'<?php echo $shop_isle_yt_link; ?>', containment:'.module-video', startAt:0, mute:true, autoPlay:true, loop:true, opacity:1, showControls:false, showYTLogo:false, vol:25}"></div>
		<!-- Youtube player end -->
		<?php
		echo '</div>';

		echo '</section><!-- .module-video -->';

	} /* END VIDEO */


	/******************************/
	/**** Products slider *********/
	/******************************/

	$shop_isle_products_slider_hide = get_theme_mod('shop_isle_products_slider_hide');

	if ( isset($shop_isle_products_slider_hide) && $shop_isle_products_slider_hide != 1 ) {
		echo '<section class="home-product-slider">';
	} elseif ( is_customize_preview() ) {
		echo '<section class="home-product-slider shop_isle_hidden_if_not_customizer">';
	}

	if ( ( isset($shop_isle_products_slider_hide) && $shop_isle_products_slider_hide != 1)  || is_customize_preview() ) {

		echo '<div class="container">';

		$shop_isle_products_slider_title = get_theme_mod('shop_isle_products_slider_title',__( 'Exclusive products', 'shop-isle' ));
		$shop_isle_products_slider_subtitle = get_theme_mod('shop_isle_products_slider_subtitle',__( 'Special category of products', 'shop-isle' ));

		if( !empty($shop_isle_products_slider_title) || !empty($shop_isle_products_slider_subtitle) ) {
			echo '<div class="row">';
			echo '<div class="col-sm-6 col-sm-offset-3">';
			if( !empty($shop_isle_products_slider_title) ) {
				echo '<h2 class="module-title font-alt home-prod-title">'.$shop_isle_products_slider_title.'</h2>';
			}
			if( !empty($shop_isle_products_slider_subtitle) ) {
				echo '<div class="module-subtitle font-serif home-prod-subtitle">'.$shop_isle_products_slider_subtitle.'</div>';
			}
			echo '</div>';
			echo '</div><!-- .row -->';
		}

		$shop_isle_products_slider_category = get_theme_mod('shop_isle_products_slider_category');

		if( !empty($shop_isle_products_slider_category) && ($shop_isle_products_slider_category != '-') ) {

			$shop_isle_products_slider_args = array(
				'post_type' => 'product',
				'posts_per_page' => 10,
				'tax_query' => array(
					array(
						'taxonomy' => 'product_cat',
						'field'    => 'term_id',
						'terms'    => $shop_isle_products_slider_category,
					)),
				'meta_query' => array(
					array(
						'key' => '_visibility',
						'value' => 'hidden',
						'compare' => '!=',
					)),
				);

			$shop_isle_products_slider_loop = new WP_Query( $shop_isle_products_slider_args );

			if( $shop_isle_products_slider_loop->have_posts() ) {

				echo '<div class="row">';

				echo '<div class="owl-carousel text-center" data-items="5" data-pagination="false" data-navigation="false">';

				while ( $shop_isle_products_slider_loop->have_posts() ) {

					$shop_isle_products_slider_loop->the_post();

					echo '<div class="owl-item">';
					echo '<div class="col-sm-12">';
					echo '<div class="ex-product">';
					if( function_exists('woocommerce_get_product_thumbnail') ) {
						echo '<a href="'.esc_url( get_permalink() ).'">' . woocommerce_get_product_thumbnail().'</a>';
					}
					echo '<h4 class="shop-item-title font-alt"><a href="'.esc_url( get_permalink() ).'">'.get_the_title().'</a></h4>';

					$rating_html = '';
					if( function_exists( 'method_exists' ) && method_exists( $product, 'get_rating_html' ) && method_exists( $product, 'get_average_rating' ) ) {
						$shop_isle_avg = $product->get_average_rating();
						if( !empty($shop_isle_avg) ) {
							$rating_html = $product->get_rating_html( $shop_isle_avg );
						}
					}

					if ( !empty($rating_html) && get_option( 'woocommerce_enable_review_rating' ) === 'yes' ) {
						echo '<div class="product-rating-home">' . $rating_html . '</div>';
					}
					if( function_exists('method_exists') && method_exists( $product, 'get_display_price' ) ) {
						$shop_isle_price = $product->get_display_price();
					}
					if( function_exists('get_woocommerce_currency_symbol') && !empty($product) && !empty( $shop_isle_price ) ) {
						if( function_exists('get_woocommerce_price_format') ) {
							$format_string = get_woocommerce_price_format();
						}
						if( !empty($format_string) ) {
							switch ( $format_string ) {
								case '%1$s%2$s' :
									echo '<span class="shop-item-price"><span class="shop-item-currency">'.get_woocommerce_currency_symbol().'</span>'.$shop_isle_price.'</span>';
									break;
								case '%2$s%1$s' :
									echo '<span class="shop-item-price">'.$shop_isle_price.'<span class="shop-item-currency">'.get_woocommerce_currency_symbol().'</span></span>';
									break;
								case '%1$s&nbsp;%2$s' :
									echo '<span class="shop-item-price"><span class="shop-item-currency">'.get_woocommerce_currency_symbol().'</span> '.$shop_isle_price.'</span>';
									break;
								case '%2$s&nbsp;%1$s' :
									echo '<span class="shop-item-price">'.$shop_isle_price.' <span class="shop-item-currency">'.get_woocommerce_currency_symbol().'</span></span>';
									break;
							}
						} else {
							echo get_woocommerce_currency_symbol().$shop_isle_price;
						}
					}
					echo '</div><!-- .ex-product -->';
					echo '</div><!-- .col-sm-12 -->';
					echo '</div><!-- .owl-item -->';

				}

				wp_reset_postdata();

				echo '</div><!-- .owl-carousel -->';

				echo '</div><!-- .row -->';

			}

		} else {

			$shop_isle_products_slider_args = array(
				'post_type' => 'product',
				'posts_per_page' => 10,
				'meta_query' => array(
					array(
						'key' => '_visibility',
						'value' => 'hidden',
						'compare' => '!=',
					)),
				);

			$shop_isle_products_slider_loop = new WP_Query( $shop_isle_products_slider_args );

			if( $shop_isle_products_slider_loop->have_posts() ) {

				echo '<div class="row">';

				echo '<div class="owl-carousel text-center" data-items="5" data-pagination="false" data-navigation="false">';

				while ( $shop_isle_products_slider_loop->have_posts() ) {

					$shop_isle_products_slider_loop->the_post();

					echo '<div class="owl-item">';
					echo '<div class="col-sm-12">';
					echo '<div class="ex-product">';
					if( function_exists('woocommerce_get_product_thumbnail') ) {
						echo '<a href="'.esc_url( get_permalink() ).'">' . woocommerce_get_product_thumbnail().'</a>';
					}
					echo '<h4 class="shop-item-title font-alt"><a href="'.esc_url( get_permalink() ).'">'.get_the_title().'</a></h4>';

					$rating_html = '';
					if( function_exists( 'method_exists' ) && method_exists( $product, 'get_rating_html' ) && method_exists( $product, 'get_average_rating' ) ) {
						$shop_isle_avg = $product->get_average_rating();
						if( !empty($shop_isle_avg) ) {
							$rating_html = $product->get_rating_html( $shop_isle_avg );
						}
					}

					if ( !empty($rating_html) && get_option( 'woocommerce_enable_review_rating' ) === 'yes' ) {
						echo '<div class="product-rating-home">' . $rating_html . '</div>';
					}
					if( function_exists('method_exists') && method_exists( $product, 'get_display_price' ) ) {
						$shop_isle_price = $product->get_display_price();
					}
					if( function_exists('get_woocommerce_currency_symbol') && !empty($product) && !empty( $shop_isle_price ) ) {
						if( function_exists('get_woocommerce_price_format') ) {
							$format_string = get_woocommerce_price_format();
						}
						if( !empty($format_string) ) {
							switch ( $format_string ) {
								case '%1$s%2$s' :
									echo '<span class="shop-item-price"><span class="shop-item-currency">'.get_woocommerce_currency_symbol().'</span>'.$shop_isle_price.'</span>';
									break;
								case '%2$s%1$s' :
									echo '<span class="shop-item-price">'.$shop_isle_price.'<span class="shop-item-currency">'.get_woocommerce_currency_symbol().'</span></span>';
									break;
								case '%1$s&nbsp;%2$s' :
									echo '<span class="shop-item-price"><span class="shop-item-currency">'.get_woocommerce_currency_symbol().'</span> '.$shop_isle_price.'</span>';
									break;
								case '%2$s&nbsp;%1$s' :
									echo '<span class="shop-item-price">'.$shop_isle_price.' <span class="shop-item-currency">'.get_woocommerce_currency_symbol().'</span></span>';
									break;
							}
						} else {
							echo get_woocommerce_currency_symbol().$shop_isle_price;
						}
					}
					echo '</div><!-- .ex-product -->';
					echo '</div><!-- .col-sm-12 -->';
					echo '</div><!-- .owl-item -->';

				}

				wp_reset_postdata();

				echo '</div><!-- .owl-carousel -->';

				echo '</div><!-- .row -->';

			}

		}

		echo '</div><!-- .container -->';

		echo '</section><!-- .home-product-slider -->';
	}

	get_footer();

} else {
	include( get_page_template() );
}
?>