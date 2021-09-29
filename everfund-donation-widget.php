<?php

/**
 * Plugin Name: Everfund SDK
 * Plugin URI: https://everfund.co.uk
 * Description: This plugin allows Everfund's non-profits to easily get access to Everfund's Donation Widget, thought the button component in the editor! 
 * Tags: donation, donations, nonprofit, nonprofits, charity, charities, donation widget, fundraising, payment, payments, crowdfunding, campaign, stripe, campaigns, social causes, causes, credit card, credit cards, bacs, direct-debits, 
 * Requires at least: 5.7
 * Requires PHP: 7.0
 * Version: 1.0.0
 * Author: Everfund
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       everfund
 *
 * @package           everfund
 */


function create_block_everfund_new_block_init()
{
	register_block_type_from_metadata(__DIR__);
}



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


add_action('plugins_loaded', 'everfund_show_apple_pay_domain_verification_file');
add_action('init', 'create_block_everfund_new_block_init');
add_action('wp_head', 'everfund_donation_script');
