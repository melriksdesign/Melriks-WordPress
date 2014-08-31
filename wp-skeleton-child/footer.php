<?php
/**
 * @package WordPress
 * @subpackage WP-Skeleton
 */
?>
</div></div></div></div><!-- content div -->

<div class="footer" id="footer">
<div class="footer-inner container">
<div class="row">
            <?php if ( is_active_sidebar( 'footer-sidebar' ) ) : ?>
			<?php dynamic_sidebar( 'footer-sidebar' ); ?>
            <?php else : ?><p>You need to drag a widget into your sidebar in the WordPress Admin</p>
	        <?php endif; ?> 
</div></div></div>

<div class="copyright" id="copyright">
<div class="container">
<div class="row">
<p>&copy; Copyright <?php echo date('Y'); ?> <?php bloginfo('name'); ?> </p>
</div></div></div>
            
                                            
<?php wp_footer(); ?>
   
</body>
</html>