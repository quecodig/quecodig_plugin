<?php
	if ( ! defined( 'ABSPATH' ) ) {
		exit; // Bloquear acceso de manera directa.
	}

	/*
	* Optimization
	* HTML, CSS, JavaScript
	* @Source: https://gist.github.com/sethbergman/d07e879200bef6862131
	*/
	class WP_HTML_Compression{
		// Settings
		protected $compress_css = true;
		protected $compress_js = true;
		protected $info_comment = true;
		protected $remove_comments = true;

		// Variables
		protected $html;
		public function __construct($html){
			if (!empty($html)){
				$this->parseHTML($html);
			}
		}
		public function __toString(){
			return $this->html;
		}
		protected function bottomComment($raw, $compressed){
			$raw = strlen($raw);
			$compressed = strlen($compressed);

			$savings = ($raw-$compressed) / $raw * 100;

			$savings = round($savings, 2);

			return '<!--Qué Código WP HTML compressed, size saved '.$savings.'%. From '.$raw.' bytes, now '.$compressed.' bytes-->';
		}
		protected function minifyHTML($html){
			$pattern = '/<(?<script>script).*?<\/script\s*>|<(?<style>style).*?<\/style\s*>|<!(?<comment>--).*?-->|<(?<tag>[\/\w.:-]*)(?:".*?"|\'.*?\'|[^\'">]+)*>|(?<text>((<[^!\/\w.:-])?[^<]*)+)|/si';
			preg_match_all($pattern, $html, $matches, PREG_SET_ORDER);
			$overriding = false;
			$raw_tag = false;
			// Variable reused for output
			$html = '';
			foreach ($matches as $token){
				$tag = (isset($token['tag'])) ? strtolower($token['tag']) : null;

				$content = $token[0];

				if (is_null($tag)){
					if ( !empty($token['script']) ){
						$strip = $this->compress_js;
					}else if ( !empty($token['style']) ){
						$strip = $this->compress_css;
					}else if ($content == '<!--Qué Código WP HTML no compression-->'){
						$overriding = !$overriding;
						// Don't print the comment
						continue;
					}else if ($this->remove_comments){
						if (!$overriding && $raw_tag != 'textarea'){
	   						// Remove any HTML comments, except MSIE conditional comments
							$content = preg_replace('/<!--(?!\s*(?:\[if [^\]]+]|<!|>))(?:(?!-->).)*-->/s', '', $content);
						}
					}
				}else{
					if ($tag == 'pre' || $tag == 'textarea'){
						$raw_tag = $tag;
					}else if ($tag == '/pre' || $tag == '/textarea'){
						$raw_tag = false;
					}else{
						if ($raw_tag || $overriding){
							$strip = false;
						}else{
							$strip = true;

							// Remove any empty attributes, except:
							// action, alt, content, src
							$content = preg_replace('/(\s+)(\w++(?<!\baction|\balt|\bcontent|\bsrc)="")/', '$1', $content);

							// Remove any space before the end of self-closing XHTML tags
							// JavaScript excluded
							$content = str_replace(' />', '/>', $content);
						}
					}
				}

				if ($strip){
					$content = $this->removeWhiteSpace($content);
				}

				$html .= $content;
			}

			return $html;
		}

		public function parseHTML($html){
			$this->html = $this->minifyHTML($html);

			if ($this->info_comment){
				$this->html .= "\n" . $this->bottomComment($html, $this->html);
			}
		}

		protected function removeWhiteSpace($str){
			$str = str_replace("\t", ' ', $str);
			$str = str_replace("\n",  '', $str);
			$str = str_replace("\r",  '', $str);

			while (stristr($str, '  ')){
				$str = str_replace('  ', ' ', $str);
			}

			return $str;
		}
	}

	if(!function_exists('quecodig_compression_finish')){
		function quecodig_compression_finish($html){
			return new WP_HTML_Compression($html);
		}
	}

	if(!function_exists('quecodig_compression_start')){
		function quecodig_compression_start(){
			ob_start('quecodig_compression_finish');
		}
	}

	/**
	* Manage WooCommerce styles and scripts.
	*/
	if(!function_exists('quecodig_woocommerce_script_cleaner')){
		function quecodig_woocommerce_script_cleaner() {
			include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
			if(is_plugin_active( 'woocommerce/woocommerce.php' ) ){
				// Remove the generator tag
				remove_action( 'wp_head', array( $GLOBALS['woocommerce'], 'generator' ) );
				// Unless we're in the store, remove all the cruft!
				if ( ! is_woocommerce() && ! is_cart() && ! is_checkout() ) {
					wp_dequeue_style( 'woocommerce_frontend_styles' );
					wp_dequeue_style( 'woocommerce-general');
					wp_dequeue_style( 'woocommerce-layout' );
					wp_dequeue_style( 'woocommerce-smallscreen' );
					wp_dequeue_style( 'woocommerce_fancybox_styles' );
					wp_dequeue_style( 'woocommerce_chosen_styles' );
					wp_dequeue_style( 'woocommerce_prettyPhoto_css' );
					wp_dequeue_script( 'selectWoo' );
					wp_deregister_script( 'selectWoo' );
					wp_dequeue_script( 'wc-add-payment-method' );
					wp_dequeue_script( 'wc-lost-password' );
					wp_dequeue_script( 'wc_price_slider' );
					wp_dequeue_script( 'wc-single-product' );
					wp_dequeue_script( 'wc-add-to-cart' );
					wp_dequeue_script( 'wc-cart-fragments' );
					wp_dequeue_script( 'wc-credit-card-form' );
					wp_dequeue_script( 'wc-checkout' );
					wp_dequeue_script( 'wc-add-to-cart-variation' );
					wp_dequeue_script( 'wc-single-product' );
					wp_dequeue_script( 'wc-cart' );
					wp_dequeue_script( 'wc-chosen' );
					wp_dequeue_script( 'woocommerce' );
					wp_dequeue_script( 'prettyPhoto' );
					wp_dequeue_script( 'prettyPhoto-init' );
					wp_dequeue_script( 'jquery-blockui' );
					wp_dequeue_script( 'jquery-placeholder' );
					wp_dequeue_script( 'jquery-payment' );
					wp_dequeue_script( 'fancybox' );
					wp_dequeue_script( 'jqueryui' );
				}
			}
		}
	}