# wp-IPFS
=== IPFS Image with Fallback ===<br>
Contributors: antonio<br>
Tags: ipfs, image, fallback, web3, nft, decentralized<br>
Requires at least: 5.5<br>
Tested up to: 6.7<br>
Requires PHP: 7.4<br>
Stable tag: 1.0.0<br>
License: GPLv2 or later<br>
License URI: https://www.gnu.org/licenses/gpl-2.0.html<br>

Display images from IPFS — automatically shows a fallback image if the IPFS gateway fails to load.

== Description ==

This lightweight plugin lets you embed IPFS-hosted images in posts, pages, or widgets using a simple shortcode.  
It starts with your fallback image (local or external) and swaps to the IPFS version only if it loads successfully — all done client-side with no server overhead.

Perfect for NFT displays, decentralized blogs, Web3 sites, or anywhere you want IPFS resilience without broken images.

Features:
* Client-side fallback (no slow server checks)
* Supports any public IPFS gateway (ipfs.io, cloudflare-ipfs.com, dweb.link, …)
* Lazy loading support
* Fully escape/sanitize output
* Tiny JS (~1 KB inline)

== Installation ==

1. Upload the `ipfs-image-fallback` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. That's it — no settings page needed!

== Usage ==

Use the shortcode `[ipfs_image]` anywhere in posts, pages, or classic editor.

Basic example:<br>
[ipfs_image cid="QmXoypizjW3WknFiJnKLwHCnL72vedxjQkDDP1mXWo6uco" fallback="https://your-site.com/images/fallback.png"]


Recommended full example:<br>
[ipfs_image <br>
    cid="bafybeihkov3viis6kh3mvp7xzt3xt2xuyxt3z3oebx3l4ps7j4opho3lku" <br>
    gateway="https://cloudflare-ipfs.com/ipfs/" <br>
    fallback="/wp-content/uploads/fallback-not-found.jpg"<br>
    alt="Decentralized artwork"<br>
    class="aligncenter size-large wp-image-custom"<br>
    width="800"<br>
    height="600"<br>
    loading="lazy"<br>
]


**Parameters:**

- **cid** (required) — The IPFS Content Identifier (CID) of your image
- **fallback** (required) — URL or relative path to fallback image
- **gateway** (optional) — IPFS gateway base URL. Default: `https://ipfs.io/ipfs/`
- **alt** (optional) — Image alt text. Default: "IPFS content image"
- **class** (optional) — CSS classes. Default: "ipfs-image"
- **width** / **height** (optional) — Dimensions in pixels
- **loading** (optional) — "lazy" or "eager". Default: "lazy"

== Frequently Asked Questions ==

= Why start with fallback instead of IPFS? =
Public gateways can be slow or temporarily down. Starting with fallback prevents ugly broken-image icons and improves perceived performance.

= Can I use my own IPFS gateway or pinning service? =
Yes — just change the `gateway` parameter (e.g. your own domain or Pinata/Submarine).

= Does it work with Gutenberg / block editor? =
Yes — add it via Shortcode block or Classic block.

== Changelog ==

= 1.0.0 =
* Initial release

Enjoy decentralized images! 🚀
