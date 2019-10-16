<?php
/**
 * Plugin Name: WX Socical Share
 * Version: 1.0.0
 * Description: Share Post in Facebook, Twiter, Google
 * Author: Thanh Chinh
 * Author URI: http://webxanh.vn
 * Plugin URI: http://webxanh.vn
 * Text Domain: wx-social-share
 */
if(!class_exists('WX_Social_Share')){
    class WX_Social_Share{
          public $version = '1.0.0';
          public $iconShow;
          public $type;
          public $imageFB, $imageTW, $imageGG;
          public function __construct()
          {
              if(maybe_unserialize(get_option( 'wx-icon-show' ))){
                  $this->iconShow = maybe_unserialize(get_option( 'wx-icon-show' ));
              }
              if(get_option( 'wx-radio-type-bottom' )){
                  $this->type = get_option( 'wx-radio-type-bottom' );
              }
              add_action('admin_menu', array($this, 'wx_social_admin_menu'));
              add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
              add_action( 'wp_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
              add_action( 'admin_init', array($this,'wx_social_add_setting_field') );
              add_filter('the_content', array($this,'wx_social_fontend_display'));
          }
          public function admin_enqueue_scripts(){
              wp_enqueue_style('wx-social-admin-styles', plugins_url('/style.css', __FILE__), array(), $this->version, 'all');
          }
          public function wx_social_admin_menu(){
            add_options_page('WX Social Share', 'WX Social Share', 'manage_options', 'wx-social-share', array($this, 'wx_social_admin_option'));
          }
          public function wx_social_add_setting_field(){
              register_setting( 'wx-social-settings-group', 'wx-radio-position' );
              register_setting( 'wx-social-settings-group', 'wx-radio-type-bottom' );
              register_setting( 'wx-social-settings-group', 'wx-icon-show' );
          }
          public function wx_social_admin_option(){

              ?>
              <form action="options.php" method="POST">
                  <?php settings_fields( 'wx-social-settings-group' ); ?>
                  <?php do_settings_sections( 'wx-social-settings-group' ); ?>
              <h1><?php _e('Social Share Setting ', 'wx-social-share'); ?></h1>
              <p><?php _e('Please select option setting', 'wx-social-share')?></p>
              <h4><?php _e('1. Display position bottom share fontend', 'wx-social-share')?></h4>
              <p>
                  <input type="radio" name="wx-radio-position" value="After" <?php checked( get_option( 'wx-radio-position' ), 'After' ); ?>> <?php _e('After', 'wx-social-share')?><br/>
                  <input type="radio" name="wx-radio-position" value="Before" <?php checked( get_option( 'wx-radio-position' ), 'Before' ); ?>> <?php _e('Before', 'wx-social-share')?>
              </p>
              <h4><?php _e('2. Option type buttom Social Share', 'wx-social-share')?></h4>
              <div class="type-box-bottom">
              <p><input type="radio" name="wx-radio-type-bottom" value="default" <?php checked( get_option( 'wx-radio-type-bottom' ), 'default' ); ?>> <?php _e('Default', 'wx-social-share')?><br/> <img src="<?php echo plugin_dir_url( __FILE__ ) . 'images/default-bg.gif'; ?>"/></p>
              <p><input type="radio" name="wx-radio-type-bottom" value="type1" <?php checked( get_option( 'wx-radio-type-bottom' ), 'type1' ); ?>> <?php _e('Type 1', 'wx-social-share')?><br/> <img src="<?php echo plugin_dir_url( __FILE__ ) . 'images/type-1.gif'; ?>"/></p>
              <p><input type="radio" name="wx-radio-type-bottom" value="type2" <?php checked( get_option( 'wx-radio-type-bottom' ), 'type2' ); ?>> <?php _e('Type 2', 'wx-social-share')?><br/> <img src="<?php echo plugin_dir_url( __FILE__ ) . 'images/type-2.gif'; ?>"/></p>
              <p><input type="radio" name="wx-radio-type-bottom" value="type3" <?php checked( get_option( 'wx-radio-type-bottom' ), 'type3' ); ?>> <?php _e('Type 3', 'wx-social-share')?><br/> <img src="<?php echo plugin_dir_url( __FILE__ ) . 'images/type-3.gif'; ?>"/></p>
              </div>
              <h4><?php _e('3. Not show icon bottom fontend', 'wx-social-share')?></h4>
              <p><input type="checkbox" name="wx-icon-show[fb]" value="Facebook" <?php if($this->iconShow['fb']) checked( $this->iconShow['fb'], 'Facebook' ); ?>> <?php _e('Facebook', 'wx-social-share')?><br></p>
              <p><input type="checkbox" name="wx-icon-show[tw]" value="Twitter" <?php if($this->iconShow['tw']) checked( $this->iconShow['tw'], 'Twitter' ); ?>> <?php _e('Twitter', 'wx-social-share')?><br></p>
              <p><input type="checkbox" name="wx-icon-show[gg]" value="Gooogle" <?php if($this->iconShow['gg']) checked( $this->iconShow['gg'], 'Gooogle' ); ?>> <?php _e('Google', 'wx-social-share')?><br></p>
                  <?php submit_button(); ?>
               <p><?php _e('Built and Developed by Webxanh.vn', 'wx-social-share')?></p>
              </form>
          <?php }
          public function wx_social_fontend_display($content){
               if($this->type == 'type1'){
                   $this->imageFB = 4;
                   $this->imageTW = 5;
                   $this->imageGG = 6;
               }elseif($this->type == 'type2'){
                   $this->imageFB = 7;
                   $this->imageTW = 8;
                   $this->imageGG = 9;
               }elseif($this->type == 'type3'){
                   $this->imageFB = 10;
                   $this->imageTW = 11;
                   $this->imageGG = 12;
               }else{
                   $this->imageFB = 1;
                   $this->imageTW = 2;
                   $this->imageGG = 3;
               }
               $wxFontend = '<div class="wx-social-view"><span class="wx-title-fontend">Chia sẻ ngay</span>';
               if(!isset($this->iconShow['fb'])){
                   $wxFontend .= '<span><a href="https://www.facebook.com/sharer/sharer.php?u='.get_permalink().'"><img src="'.plugin_dir_url( __FILE__ ) . 'images/icon-'.$this->imageFB.'.gif'.'"/></a> </span>';
               }
               if(!isset($this->iconShow['tw'])){
                   $wxFontend .= '<span><a href="http://www.twitter.com/share?url='.get_permalink().'"><img src="'.plugin_dir_url( __FILE__ ) . 'images/icon-'.$this->imageTW.'.gif'.'"/></a> </span>';
               }
               if(!isset($this->iconShow['gg'])){
                   $wxFontend .= '<span><a href="https://plus.google.com/share?url='.get_permalink().'"><img src="'.plugin_dir_url( __FILE__ ) . 'images/icon-'.$this->imageGG.'.gif'.'"/></a> </span>';
               }
               $wxFontend .= '</div>';
               if(get_option( 'wx-radio-position' )=='After'){
                   return $wxFontend.$content;
               }else{
                   return $content.$wxFontend;
               }

              ?>

         <?php }
    }
    new WX_Social_Share();
}