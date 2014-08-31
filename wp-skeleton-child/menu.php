<?php 
/**
 * @package WordPress
 * @subpackage WP-Skeleton
 */
?>
<div class="header" id="header">  
<div class="header-inner container">
<div class="row">
    <div class="seven columns alpha"> 
        <div class="logo">
            <a href="<?php echo home_url(); //make logo a home link?>">
            <img src=" <?php echo get_stylesheet_directory_uri(); ?>/images/logo.png" alt="<?php echo get_bloginfo('name');?>" />
            </a>
        </div>
    </div> 
    
    <div class="nine columns omega">
	<!-- phone -->
	<div class="tel">999-999-9999</div>
    <!--  the Menu -->
    <?php wp_nav_menu( array( 'theme_location' => 'primary' ) ); ?>
    </div>
</div><!-- row -->

<?php /* http://codex.wordpress.org/Conditional_Tags 
http://codex.wordpress.org/Function_Reference/do_shortcode  */ ?>

<?php if ( is_front_page() ): ?>
<div class="row" id="home-slider-container">
<div id="home-slider">
<?php echo do_shortcode('[metaslider id=100]'); ?>
</div>
</div><!-- row -->
<?php endif; ?>

</div><!-- end header-inner -->
</div> <!--  End header -->

<div class="middle" id="middle">
<div class="middle-inner container">
<div class="row">