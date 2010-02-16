<?php
/*
Plugin Name: Google Buzz Button For Wordpress
Plugin URI: http://www.clickonf5.org/google-buzz-button-wordpress
Description: It adds Google buzz button to your post/page
Version: 1.0.0	
Author: Tejaswini, Sanjeev
Author URI: http://www.clickonf5.org/
Wordpress version supported: 2.7 and above
*/

/*  Copyright 2009  Internet Techies  (email : tedeshpa@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
define("GOOGLE_BUZZ_BUTTON","1.0",false);
function google_buzz_url( $path = '' ) {
	global $wp_version;
	if ( version_compare( $wp_version, '2.8', '<' ) ) { // Using WordPress 2.7
		$folder = dirname( plugin_basename( __FILE__ ) );
		if ( '.' != $folder )
			$path = path_join( ltrim( $folder, '/' ), $path );

		return plugins_url( $path );
	}
	return plugins_url( $path, __FILE__ );
}
//on activation, your CAA forms options will be populated. Here a single option is used which is actually an array of multiple options
function activate_google_buzz() {

global $google_buzz_options;
	$google_buzz_options = array('rel'=> 'nofollow', 
	                           'location'=>'after',
							   'width'=>'50',
							   'height'=>'58');
	add_option('google_buzz_options',$google_buzz_options);
}	
global $google_buzz_options;	
$google_buzz_options = get_option('google_buzz_options');			  
register_activation_hook( __FILE__, 'activate_google_buzz' );

function add_google_buzz_button_automatic($content){ 
 global $google_buzz_options, $post;
 $p_title = get_the_title($post->ID);
 $google_buzz_button = '<a class="google_buzz"  
href="http://www.google.com/reader/link?url='.get_permalink( $post->ID ).'&title='.str_replace(' ','+',$p_title).'&srcURL='.get_bloginfo( 'url' ).'" target="_blank" rel="'.$google_buzz_options['rel'].'"><img
src="'.google_buzz_url('/images/google-buzz.png').'" alt="Google Buzz" /></a>';
  if($google_buzz_options['location'] == 'before' ){
    $content = $google_buzz_button.$content;
  }
  else{
    $content = $content.$google_buzz_button;
  }
  return $content;
}
if ($google_buzz_options['location'] != 'manual'){
add_filter('the_content','add_google_buzz_button_automatic'); 
}

function add_google_buzz_button(){
 global $google_buzz_options, $post;
 $p_title = get_the_title($post->ID);
 $google_buzz_button = '<a class="google_buzz"  
href="http://www.google.com/reader/link?url='.get_permalink( $post->ID ).'&title='.str_replace(' ','+',$p_title).'&srcURL='.get_bloginfo( 'url' ).'" target="_blank" rel="'.$google_buzz_options['rel'].'"><img
src="'.google_buzz_url('/images/google-buzz.png').'" alt="Google Buzz" width="'.$google_buzz_options['width'].'" height="'.$google_buzz_options['height'].'" /></a>';
 echo $google_buzz_button;
}

// function for adding settings page to wp-admin
function google_buzz_settings() {
	add_options_page('Google Buzz', 'Google Buzz', 9, basename(__FILE__), 'google_buzz_options_form');
}

function google_buzz_options_form(){ 
global $google_buzz_options;
?>
<div class="wrap">

<div id="poststuff" class="metabox-holder has-right-sidebar" style="float:right;width:30%;"> 
   <div id="side-info-column" class="inner-sidebar"> 
			<div class="postbox"> 
			  <h3 class="hndle"><span>About this Plugin:</span></h3> 
			  <div class="inside">
                <ul>
                <li><a href="http://www.clickonf5.org/google-buzz-button-wordpress" title="Google Buzz Button Wordpress Plugin Homepage" >Plugin Homepage</a></li>
                <li><a href="http://www.clickonf5.org" title="Visit Internet Techies" >Plugin Parent Site</a></li>
                <li><a href="http://www.clickonf5.org/phpbb/google-buzz-button-for-wordpress-f23/" title="Support Forum for Google Buzz Button" >Support Forum</a></li>
                <li><a href="http://www.clickonf5.org/about/tejaswini" title="Google Buzz Button Wordpress Plugin Author Page" >About the Author</a></li>
                <li><a href="http://www.clickonf5.org/go/smooth-slider/" title="Donate if you liked the plugin and support in enhancing Google Buzz button and creating new plugins" >Donate with Paypal</a></li>
                </ul> 
              </div> 
			</div> 
     </div>

     <div id="side-info-column" class="inner-sidebar"> 
			<div class="postbox"> 
			  <h3 class="hndle"><span>Credits:</span></h3> 
			  <div class="inside">
                <ul>
                <li><a href="http://www.mashable.com" title="Google Buzz Button Icon from Mashable team" >Mashable</a></li>
                </ul> 
              </div> 
			</div> 
     </div>
     
          <div id="side-info-column" class="inner-sidebar"> 
			<div class="postbox"> 
			  <h3 class="hndle"><span>Support &amp; Donations</span></h3> 
			  <div class="inside">
              </div> 
			</div> 
     </div>  
 </div> <!--end of poststuff --> 


<form method="post" action="options.php">

<?php settings_fields('google_buzz_options_group'); ?>

<h2>Google Buzz Button Options</h2> 

<table class="form-table" style="clear:none;width:70%;">

<tr valign="top">
<th scope="row">Rel Attribute</th>
<td><input type="text" name="google_buzz_options[rel]" id="item_name" class="regular-text code" value="<?php echo $google_buzz_options['rel']; ?>" /></td>
</tr>

<tr valign="top">
<th scope="row">Location of the button</th>
<td><select name="google_buzz_options[location]" id="location" >
<option value="before" <?php if ($google_buzz_options['location'] == "before"){ echo "selected";}?> >Before Content</option>
<option value="after" <?php if ($google_buzz_options['location'] == "after"){ echo "selected";}?> >After Content</option>
<option value="manual" <?php if ($google_buzz_options['location'] == "manual"){ echo "selected";}?> >Manual Insertion</option>
</select>
(Use template tag <code>add_google_buzz_button();</code> for Manual Insertion)
</td>
</tr>

<tr valign="top">
<th scope="row">Icon Width</th>
<td><input type="text" name="google_buzz_options[width]" id="item_name" class="small-text" value="<?php echo $google_buzz_options['width']; ?>" /></td>
</tr>

<tr valign="top">
<th scope="row">Icon Height</th>
<td><input type="text" name="google_buzz_options[height]" id="item_name" class="small-text" value="<?php echo $google_buzz_options['height']; ?>" /></td>
</tr>

</table>

<p class="submit">
<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
</p>

</form>

</div>
<?php }

// Hook for adding admin menus
if ( is_admin() ){ // admin actions
  add_action('admin_menu', 'google_buzz_settings');
  add_action( 'admin_init', 'register_google_buzz_settings' ); 
} 
function register_google_buzz_settings() { // whitelist options
  register_setting( 'google_buzz_options_group', 'google_buzz_options' );
}

?>