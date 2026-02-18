<?php
/**
 * Plugin Name:       IPFS Image with Fallback
 * Plugin URI:        https://fresnocs.com/ipfs-image-fallback
 * Description:       Displays an image from IPFS. Shows fallback image if IPFS fails to load (client-side).
 * Version:           1.0.0
 * Author:            Antonio / Grok Assisted
 * License:           GPL-2.0-or-later
 * Text Domain:       ipfs-image-fallback
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

define('IPFSFB_VERSION', '1.0.0');

class IPFS_Image_Fallback {

    public function __construct() {
        add_shortcode('ipfs_image', [$this, 'shortcode_ipfs_image']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_assets']);
    }

    public function enqueue_assets() {
        // Only load on front-end + when shortcode exists (very lightweight)
        if (is_admin()) {
            return;
        }

        // Tiny helper script – you can move to external file later
        $script = "
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('img[data-ipfs-fallback]').forEach(function(img) {
                var ipfsSrc = img.getAttribute('data-ipfs-src');
                if (!ipfsSrc) return;

                var test = new Image();
                test.onload = function() {
                    img.src = ipfsSrc;
                    img.removeAttribute('data-ipfs-fallback'); // clean up
                };
                test.onerror = function() {
                    // already on fallback – do nothing or add class
                    img.classList.add('ipfs-failed');
                };
                test.src = ipfsSrc;
            });
        });
        ";

        wp_register_script(
            'ipfs-fallback-helper',
            false,           // no src → inline
            [],
            IPFSFB_VERSION,
            ['in_footer' => true]
        );

        wp_add_inline_script('ipfs-fallback-helper', $script);
        wp_enqueue_script('ipfs-fallback-helper');
    }

    public function shortcode_ipfs_image($atts) {
        $atts = shortcode_atts([
            'cid'      => '',                    // required
            'gateway'  => 'https://ipfs.io/ipfs/', // or dweb.link, cloudflare-ipfs.com, etc.
            'fallback' => '',                    // required – can be full URL or /wp-content/...
            'alt'      => 'IPFS content image',
            'class'    => 'ipfs-image',
            'width'    => '',
            'height'   => '',
            'loading'  => 'lazy',
        ], $atts, 'ipfs_image');

        if (empty($atts['cid']) || empty($atts['fallback'])) {
            return '<!-- IPFS Image shortcode: missing cid or fallback -->';
        }

        $ipfs_url   = esc_url(trailingslashit($atts['gateway']) . ltrim($atts['cid'], '/'));
        $fallback   = esc_url($atts['fallback']);
        $alt        = esc_attr($atts['alt']);
        $class      = esc_attr($atts['class']);
        $width      = $atts['width'] ? ' width="' . esc_attr($atts['width']) . '"' : '';
        $height     = $atts['height'] ? ' height="' . esc_attr($atts['height']) . '"' : '';
        $loading    = in_array($atts['loading'], ['lazy','eager'], true) ? $atts['loading'] : 'lazy';

        // Start with fallback, attempt IPFS via data attribute + JS
        $html = sprintf(
            '<img src="%s" data-ipfs-src="%s" data-ipfs-fallback="1" alt="%s" class="%s" loading="%s"%s%s>',
            $fallback,
            $ipfs_url,
            $alt,
            $class . ' ipfs-fallback-image',
            $loading,
            $width,
            $height
        );

        return $html;
    }

}

// Start the plugin
new IPFS_Image_Fallback();

// Add usage page under Settings menu
add_action('admin_menu', function() {
    add_options_page(
        'IPFS Image Fallback – How to Use',
        'IPFS Image Help',
        'manage_options',
        'ipfs-image-fallback-help',
        function() {
            ?>
            <div class="wrap">
                <h1>IPFS Image with Fallback – Usage Guide</h1>
                
                <p>This plugin lets you display images from IPFS with an automatic fallback if the IPFS version fails to load.</p>
                
                <h2>Shortcode</h2>
                <pre><code>[ipfs_image cid="YOUR_CID_HERE" fallback="YOUR_FALLBACK_URL" ... ]</code></pre>
                
                <h3>Required attributes</h3>
                <ul>
                    <li><strong>cid</strong> — IPFS CID (e.g. QmXoypizjW3WknFiJnKLwHCnL72vedxjQkDDP1mXWo6uco)</li>
                    <li><strong>fallback</strong> — URL or path to backup image</li>
                </ul>
                
                <h3>Optional attributes</h3>
                <ul>
                    <li>gateway — e.g. https://cloudflare-ipfs.com/ipfs/ (default: https://ipfs.io/ipfs/)</li>
                    <li>alt — image description</li>
                    <li>class — CSS classes</li>
                    <li>width / height — dimensions</li>
                    <li>loading — "lazy" or "eager"</li>
                </ul>
                
                <h2>Example</h2>
                <pre><code>[ipfs_image 
                              cid="bafybeiabc123..."
                              gateway="https://dweb.link/ipfs/"
                              fallback="https://mysite.com/images/placeholder.jpg"
                              alt="My NFT artwork"
                              width="640" height="480"
                              loading="lazy"
                          ]</code></pre>

                <p>Questions? Feel free to reach out on FresnoCS.com.</p>
            </div>
            <?php
        }
    );
});
