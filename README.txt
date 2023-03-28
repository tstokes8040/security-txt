=== wp-security-txt ===
Plugin Name: securitytxt
Tags: security, infosec, netsec, security.txt, responsible disclosure, bug bounty
Requires at least: 5.0
Tested up to: 6.1.1
Stable tag: 6.1.1
Requires PHP: 7.0
License: MIT License
License URI: https://github.com/austinheap/wordpress-security-txt/blob/master/LICENSE.md
GitHub Plugin URI: https://github.com/austinheap/wordpress-security-txt

A plugin for serving 'security.txt' in WordPress 6.1.1+, based on configuration settings.

== Description ==

The purpose of this project is to create a set-it-and-forget-it plugin that can be installed without much effort to get a WordPress site compliant with the current [`security.txt`](https://securitytxt.org/) spec. It is therefore highly opinionated but built for configuration. It will automatically configure itself but you are encouraged to visit the plugin settings page after activating it.

[`security.txt`](https://github.com/securitytxt) is a [draft](https://tools.ietf.org/html/draft-foudil-securitytxt-00) "standard" which allows websites to define security policies. This "standard" sets clear guidelines for security researchers on how to report security issues, and allows bug bounty programs to define a scope. Security.txt is the equivalent of `robots.txt`, but for security issues.

There is a help page built into the plugin if you need help configuring it. For developers, there is [documentation for `wordpress-security-txt` online](https://austinheap.github.io/wordpress-security-txt/), the source of which is in the [`docs/`](https://github.com/austinheap/wordpress-security-txt/tree/master/docs) directory. The most logical place to start are the [docs for the `WordPress_Security_Txt` class](https://austinheap.github.io/wordpress-security-txt/packages/WordPress.Security.Txt.html).

== Installation ==

This section describes how to install `wordpress-security-txt` and get it working.

1. Upload `wordpress-security-txt` to the `/wp-content/plugins/` directory.
1. Activate the plugin through the 'Plugins' menu in WordPress.
1. Configure the plugin using the 'settings.txt' link under 'Settings'.

== Changelog ==

= 1.0.2 =
* Initial release.

== Upgrade Notice ==

= 1.0.2 =
* Initial release.
