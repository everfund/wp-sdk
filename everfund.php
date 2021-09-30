<?php
/**
 * Plugin Name: Everfund
 * Plugin URI: https://everfund.io
 * Description: Everfund takes the friction out of donating with an easy to set up donation portal for your WordPress website that help you convert more income for your charity and give your donors the best experience possible. 
 * Tags: donation, donations, nonprofit, nonprofits, charity, charities, donation widget, fundraising, payment, payments, crowdfunding, campaign, stripe, campaigns, social causes, causes, credit card, credit cards, bacs, direct-debits 
 * Requires at least: 5.3
 * Requires PHP: 7.0
 * Version: 1.1.0
 * Author: Everfund
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       everfund
 *
 * @package           everfund
 */

function everfund_show_apple_pay_domain_verification_file()
{
	if ($_SERVER["REQUEST_URI"] == "/.well-known/apple-developer-merchantid-domain-association") {
		readfile(dirname(__FILE__) . "/apple-developer-merchantid-domain-association");
		exit();
	}
}

function everfund_donation_script()
{
?>
	<script>
		! function(e, t, n, a) {
			function r() {
				if (!t.getElementById(n)) {
					var e = t.getElementsByTagName(a)[0],
						r = t.createElement(a);
					r.type = "text/javascript", r.async = !0, r.src = "https://cdn.jsdelivr.net/npm/@everfund/sdk@1.1.0/dist/m.js", e.parentNode.insertBefore(r, e)
				}
			}
			if ("function" != typeof e.Everfund) {
				var c = function() {
					c.q.push(arguments)
				};
				c.q = [], e.Everfund = c, "complete" === t.readyState ? r() : e.attachEvent ? e.attachEvent("onload", r) : e.addEventListener("load", r, !1)
			}
		}(window, document, "everfund", "script");
	</script>
<?php
};




function everfund_create_menu() {
  add_menu_page(__('Donations', 'everfund'), __('Donations', 'everfund'), 'administrator', __FILE__, 'everfund_plugin_settings_page' , 'dashicons-info');
  add_action('plugins_loaded', 'register_everfund_plugin_textdomain' );
}

function everfund_plugin_settings_page() {
?>

<link rel="stylesheet" href="<?php echo plugins_url("assets/style.css", __FILE__ );?>">
  <div class="wrap everfund-wrap">
    <div class="everfund-modal">
      <h2 class="everfund-title"><?php _e('Everfund is Installed.', 'everfund'); ?></h2>
      <p class="everfund-subtitle"><?php _e('You can now use Everfund on your website.', 'everfund'); ?></p>
      <a class="everfund-button everfund-neutral" href="https://dashboard.everfund.io/donations/"><?php _e('Go to my Widgets', 'everfund'); ?></a>
      <a class="everfund-button everfund" href="https://dashboard.everfund.io/links/widgets/"><?php _e('Go to my Donations', 'everfund'); ?></a>
	  <p class="everfund-stuck"><?php _e('Stuck? Talk to ', 'everfund'); ?> <a href="https://help.everfund.co.uk/en/"> Support </a> <?php _e(' or check out the ', 'everfund'); ?><a href="https://developer.everfund.co.uk/integrations/wordpress">developer documentation </a></p>
    </div>
  </div>

  <?php
}

add_action('plugins_loaded', 'everfund_show_apple_pay_domain_verification_file');
add_action('wp_head', 'everfund_donation_script');
add_action('admin_menu', 'everfund_create_menu');