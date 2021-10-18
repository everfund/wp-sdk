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
 * Text Domain:       everfund.io.
 */
function everfund_show_apple_pay_domain_verification_file()
{
    if ($_SERVER['REQUEST_URI'] == '/.well-known/apple-developer-merchantid-domain-association') {
        readfile(dirname(__FILE__).'/apple-developer-merchantid-domain-association');
        exit();
    }
}

function everfund_create_menu()
{
    add_menu_page(__('Donations', 'everfund'), __('Donations', 'everfund'), 'administrator', __FILE__, 'everfund_plugin_settings_page', 'dashicons-info');
    add_action('plugins_loaded', 'register_everfund_plugin_textdomain');
}

function everfund_admin_styles()
{
    wp_register_style('custom_wp_admin_css', plugins_url('assets/style.css', __FILE__), false, '1.0.0');
    wp_enqueue_style('custom_wp_admin_css');
}

function everfund_plugin_settings_page()
{
    ?>
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

function everfund_sdk_script()
{
    wp_enqueue_script('everfund', 'https://cdn.jsdelivr.net/npm/@everfund/sdk@1.1.0/dist/m.js', false);
}

function everfund_send_cors_headers($headers)
{
    $allowed_domains = ['https://evr.fund', 'http://animals.charity', 'http://appeal.charity', 'http://emergency.charity', 'http://giveto.charity', 'http://hospice.charity', 'http://nhs.charity', 'http://shelter.charity', 'http://urgent.charity', 'http://everfund.co.uk', 'http://api.everfund.co.uk', 'http://everfund.io', 'http://api.everfund.io'];
    if (!in_array($_SERVER['HTTP_ORIGIN'], $allowed_domains)) {
        return $headers;
    }
    $headers['Access-Control-Allow-Origin'] = $_SERVER['HTTP_ORIGIN'];

    return $headers;
}

add_filter('wp_headers', 'everfund_send_cors_headers', 11, 1);
add_action('admin_enqueue_scripts', 'everfund_admin_styles');
add_action('wp_enqueue_scripts', 'everfund_sdk_script');
add_action('plugins_loaded', 'everfund_show_apple_pay_domain_verification_file');
add_action('wp_head', 'everfund_donation_script');
add_action('admin_menu', 'everfund_create_menu');
