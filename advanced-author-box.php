<?php
/*
Plugin Name: Advanced Author Box
Plugin URI: 
Description: This is a plugin that adds an author box to the end of your WordPress posts - a fork of Guerrilla's autherbox plugin but with amp support
Version: 1.5
Author: deano1987
Author URI: http://deano.me
*/

/* This code adds new profile fields to the user profile editor */
function modify_contact_methods($profile_fields) {

	// Add new fields
	$profile_fields['twitter'] = 'Twitter URL';
	$profile_fields['facebook'] = 'Facebook URL';
	$profile_fields['gplus'] = 'Google+ URL';
	$profile_fields['linkedin'] = 'Linkedin URL';
	$profile_fields['dribbble'] = 'Dribbble URL';
	$profile_fields['github'] = 'Github URL';

	// Remove old fields
	unset($profile_fields['aim']);
	unset($profile_fields['yim']);
	unset($profile_fields['jabber']);

	return $profile_fields;
}
add_filter('user_contactmethods', 'modify_contact_methods');

$opt 						= array();

$opt['avatarsize'] 		= "100";
$opt['nofollow'] 		= 0;
$opt['maincss'] 		= ".aab_wrap {
    background: #f8f8f8;
    -webkit-box-sizing: border-box;
    -moz-box-sizing: border-box;
    -ms-box-sizing: border-box;
    box-sizing: border-box;
    border: 1px solid #dadada;
    padding: 2%;
    width: 100%;
}
.aab_gravatar {
    float: left;
    margin: 0 10px 0 0;
}
.aab_text h4 {
    font-size: 20px;
    line-height: 20px;
    margin: 0 0 0 0!important;
    padding: 0;
}
.aab_social {
    float: left;
    width: 100%;
    padding-top: 10px;
}
.aab_social a {
    border: 0;
    margin-right: 10px;
}";
$opt['ampcss'] 		= ".aab_gravatar {
	float: left;
	margin-right: 10px;
}
.aab_social amp-img {
	margin:5px 5px 0px 10px;
}
.aab_social amp-img, .aab_social a {
	float:left;
}";
add_option("advanced_author_box",$opt);

if ( ! class_exists( 'WDPanelAdmin' ) ) {
	require_once('WDPanelAdmin.php');
}
if ( ! class_exists( 'AAB_WDPanelAdmin' ) ) {

	class AAB_WDPanelAdmin extends WDPanelAdmin {

		var $hook 		= 'advanced-author-box';
		var $longname	= 'Advanced Author Box Configuration';
		var $shortname	= 'Advanced Author Box';
		var $filename	= 'advanced-author-box/advanced-author-box.php';
		var $ozhicon	= 'script_link.png';

		function config_page() {
			if ( isset($_POST['submit']) ) {
				if (!current_user_can('manage_options')) die(__('You cannot edit these options.'));
				check_admin_referer('schema-breadcrumbs-updatesettings');
				
				$opt 						= array();

				$opt['nofollow'] 		= (int)$_POST['nofollow'];
				$opt['avatarsize'] 		= $_POST['avatarsize'];
				$opt['maincss'] 		= $_POST['maincss'];
				$opt['ampcss'] 		= $_POST['ampcss'];
				
				update_option('advanced_author_box', $opt);
			}
			
			$opt  = get_option('advanced_author_box');
			?>
			<div class="wrap">
				
				<h2><?php echo $this->longname; ?></h2>
				<div class="postbox-container" style="width:70%;">
					<div class="metabox-holder">	
						<div class="meta-box-sortables">
							<form action="" method="post" id="schemabreadcrumbs-conf">
								
								<?php if (function_exists('wp_nonce_field')) 		
										wp_nonce_field('schema-breadcrumbs-updatesettings');
										
								$rows = array();
								$rows[] = array(
									"id" => "nofollow",
									"label" => __('Apply Nofollow'),
									"content" => '<label>No <input type="radio" name="nofollow" id="nofollow" value="0" '.($opt['nofollow'] == 0 ? 'checked="checked"' : '').' /></label> <label>Yes <input type="radio" name="nofollow" id="nofollow" value="1" '.($opt['nofollow'] == 1 ? 'checked="checked"' : '').' /></label>',
								);
								$rows[] = array(
									"id" => "avatarsize",
									"label" => __('Avatar Size'),
									"content" => '<input type="text" name="avatarsize" id="avatarsize" value="'.htmlentities($opt['avatarsize']).'" style="width:50px" />px',
								);
								$rows[] = array(
									"id" => "maincss",
									"label" => __('Main CSS Style'),
									"content" => '<textarea name="maincss" id="maincss" style="width:50%;height:200px">'.stripslashes($opt['maincss']).'</textarea>',
								);
								$rows[] = array(
									"id" => "ampcss",
									"label" => __('AMP CSS Style'),
									"content" => '<textarea name="ampcss" id="ampcss" style="width:50%;height:200px">'.stripslashes($opt['ampcss']).'</textarea>',
								);
								
								$table = $this->form_table($rows);
								
								
								$this->postbox('breadcrumbssettings',__($this->longname), $table.'<div class="submit"><input type="submit" class="button-primary" name="submit" value="Save Settings" /></div>')
								?>
							</form>
							
						</div>
					</div>
				</div>
				<div class="postbox-container" style="width:30%;padding-left:10px;box-sizing: border-box;">
					<div class="metabox-holder">	
						<div class="meta-box-sortables">
							<center style="background-color:white;">
								<a href="https://webdesires.co.uk">
									<div style="margin-bottom:20px;padding:5px 10px 10px 10px">
										<img style="width:100%" src="https://webdesires.co.uk/wp-content/themes/webdesires/images/logo/WebDesiresLogo.png" alt="WebDesires - Web Development" title="WebDesires - Web Development" /><br>
										Looking for a developer?<br>
										Professional UK WordPress Web Development Company
									</div>
								</a>
							</center>
							<?php
								$this->plugin_like();
								$this->plugin_support();
								$this->wd_knowledge(); 
								$this->wd_news(); 
							?>
						</div>
						<br/><br/><br/>
					</div>
				</div>
			</div>
		
<?php		}
	}
	
	$ybc = new AAB_WDPanelAdmin();
}



/* This code adds the fontawesome css file to the footer of your website */
function fontawesome_authorbox() { ?>
<link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" rel="stylesheet" />
<?php } add_action( 'wp_footer', 'fontawesome_authorbox' );

/* This code adds the author box to the end of your single posts */
add_filter ('the_content', 'aab_add_post_content', 500);

function aab_add_post_content($content) {
	if (is_single() && post_type_supports(get_post_type(), 'author')) {
		if (function_exists('is_amp_endpoint') && is_amp_endpoint()) {
			function aab_add_post_content_css_styles(  ) {
				// only CSS here please...
				$opt  = get_option('advanced_author_box');
				echo $opt['ampcss'];
			}
			add_action( 'amp_post_template_css', 'aab_add_post_content_css_styles' );
			
			$opt  = get_option('advanced_author_box');
			
			$post_id = get_the_ID();
			$post = get_post($post_id);
			$post_author = get_userdata( $post->post_author );
			$post_author->name = $post_author->first_name . ' ' . $post_author->last_name;
			//$post_author = $this->get( 'post_author' );
			$content .= '
				<div class="aab_wrap">
				<div class="aab_text">
					<div class="aab_gravatar">
						'. get_avatar( $post_author->user_email, '100' ) .'
					</div>
					<h4>Author: <span><a href="' . esc_url( $post_author->user_url ) . '" title="' . esc_attr( sprintf(__("Visit %s&#8217;s website"), $post_author->name) ) . '" rel="author external ' . ($opt['nofollow'] == 1 ? 'nofollow' : '') .'">' . $post_author->name . '</a></span></h4>'. $post_author->description .'
				</div>
				<div class="clear"></div>
			';
			$content .= '
				<div class="aab_social">
				';
				if( $post_author->twitter)
					$content .= '<img src="'.plugin_dir_url( __FILE__ ).'twitter.png" /> <a href="' . esc_url( $post_author->twitter) . '" target="_blank">Twitter</a> ';
				if( $post_author->facebook)
					$content .= '<img src="'.plugin_dir_url( __FILE__ ).'facebook.png" /> <a href="' . esc_url( $post_author->facebook ) . '" target="_blank">Facebook</a> ';
				if( $post_author->gplus)
					$content .= '<img src="'.plugin_dir_url( __FILE__ ).'google.png" /> <a href="' . esc_url( $post_author->gplus ) . '" target="_blank">Google+</a> ';
				if( $post_author->linkedin )
					$content .= '<img src="'.plugin_dir_url( __FILE__ ).'linkedin.png" /> <a href="' . esc_url( $post_author->linkedin ) . '" target="_blank">Linkedin</a> ';
				if( $post_author->dribbble )
					$content .= '<a href="' . esc_url( $post_author->dribbble ) . '" target="_blank">Dribbble</a> ';
				if( $post_author->github )
					$content .= '<a href="' . esc_url( $post_author->github ) . '" target="_blank">Github</a>';
			$content .= '</div><div class="clear"></div></div>';
		} else {
		
			$opt  = get_option('advanced_author_box');
			
			$post_id = get_the_ID();
			$post = get_post($post_id);
			$post_author = get_userdata( $post->post_author );
			$post_author->name = $post_author->first_name . ' ' . $post_author->last_name;

			$content .="<style>" .$opt['maincss'] . "</style>";
			
			$content .= '
				<div class="aab_wrap">
				<div class="aab_text">
					<div class="aab_gravatar">
						'. get_avatar( get_the_author_email(), '100' ) .'
					</div>
					<h4>Author: <span><a href="' . esc_url( $post_author->user_url ) . '" title="' . esc_attr( sprintf(__("Visit %s&#8217;s website"), $post_author->name) ) . '" rel="author external ' . ($opt['nofollow'] == 1 ? 'nofollow' : '') .'">' . $post_author->name . '</a></span></h4>'. $post_author->description .'
				</div>
			';
			$content .= '
				<div class="aab_social">
				';
				if( get_the_author_meta('twitter',get_query_var('author') ) )
					$content .= '<a href="' . esc_url( get_the_author_meta( 'twitter' ) ) . '" target="_blank"><i class="fa fa-twitter"></i> Twitter</a> ';
				if( get_the_author_meta('facebook',get_query_var('author') ) )
					$content .= '<a href="' . esc_url( get_the_author_meta( 'facebook' ) ) . '" target="_blank"><i class="fa fa-facebook"></i> Facebook</a> ';
				if( get_the_author_meta('gplus',get_query_var('author') ) )
					$content .= '<a href="' . esc_url( get_the_author_meta( 'gplus' ) ) . '" target="_blank"><i class="fa fa-google-plus"></i> Google+</a> ';
				if( get_the_author_meta('linkedin',get_query_var('author') ) )
					$content .= '<a href="' . esc_url( get_the_author_meta( 'linkedin' ) ) . '" target="_blank"><i class="fa fa-linkedin"></i> Linkedin</a> ';
				if( get_the_author_meta('dribbble',get_query_var('author') ) )
					$content .= '<a href="' . esc_url( get_the_author_meta( 'dribbble' ) ) . '" target="_blank"><i class="fa fa-dribbble"></i> Dribbble</a> ';
				if( get_the_author_meta('github',get_query_var('author') ) )
					$content .= '<a href="' . esc_url( get_the_author_meta( 'github' ) ) . '" target="_blank"><i class="fa fa-github"></i> Github</a>';
			$content .= '</div><div class="clear"></div></div>';
		}
	}
	return $content;
}
?>