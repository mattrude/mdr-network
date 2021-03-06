# WordPres Plugin MDR Network

This is a group of mostly small plugins needed for multi site installs.  Most of these plugins only do one thing, but a few others add major updates to WordPress.

This repository should be put in the root of `wp-content/mu-plugins` so each active plugin is directly in mu-plugins, not in a sub folder.  If you do not have a mu-plugins folder, you may simply run the following from the your wordpress root directory:

    git clone git://github.com/mattrude/mdr-network.git wp-content/mu-plugins

### Included Plugins

* **Category Excluder** - Allows a user to select the categories they wish to exclude and where they would like to excluded the categories from (main page, feed, archives). See: Tools -> Category Excluder
* **Contact Info** - Adds Custome User Contact Methods. Adds Facebook URL, Google Talk, & Twitter ID. Removes AIM & Jabber. See: Users -> Your Profile
* **Enable Update Services** - Enables the Update Service for network installs of WordPress. See: Settings -> Writing
* **Favicon** - Provides favicon support for WordPress network installs. If a user dosen't have a favicon.ico or favicon.png file in their files directory, it will use their Gravatar image, if they don't have that, it will use the site's default.
* **Feedburner** - Redirects all sites feeds to the entred Feedburner feed. See: Settings -> Reading
* **Picasa Captioner** - Fix for WordPress to read Picasa Captions from EXIF info properly.
* **Proper Network Activation** - Use the network activation feature of WP MultiSite without problems.
* **Sitemap Generator** - Automatic generate standard XML sitemap (http://example.com/sitemap.xml) that supports the protocol including Google, Yahoo, MSN, Ask.com, and others. No files stored on your disk, the sitemap.xml file is generate as needed, like your feeds.
* **Sub Pages widget** - Show only the sub pages, if the current page has sub pages.
* **Time Since** - Adds a Time Since shotcode "[ts date'1980-06-19']" that will display the years since the date provided.
* **Typekit Fonts** - Provides Typekit fonts to a website by adding the needed java code to the pages. See: Appearance -> Fonts
* **Webmaster Tools** - Provides Webmaster site verification scripts for Google, Yahoo, & Bing. Plugin also provides Google Analytics Tracking Script for registered sites. See: Tools -> Webmaster Tools

Their are also two disable plugins that you may renable by simply copying them to the root folder.

* **CDN MDR Tools** - Redirect URLs to a configurable address.
* **Page Speed** - Provides small imporvments for page loading, currently set to remove l10n.js.

### Rebuilding the POT file

    xgettext --keyword=__ --keyword=_e  --from-code=php --language=php *.php --package-version=1.o \
    --package-name=mdr-network --output=languages/mdr-network.pot

## License
These Plugins are free software; you may redistribute and/or modify them under the terms of the GNU General Public License version 2 as published by the Free Software Foundation (http://www.fsf.org/).

                  GNU GENERAL PUBLIC LICENSE
                     Version 2, June 1991
    
    Copyright (C) 1989, 1991 Free Software Foundation, Inc.,
    51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA
    Everyone is permitted to copy and distribute verbatim copies
    of this license document, but changing it is not allowed.

## Issuess
If you are having issues using mdr-network, please file a bug on this projects github [Issues](https://github.com/mattrude/mdr-network/issues) page.
