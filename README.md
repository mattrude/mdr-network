This is a group of mostly small plugins needed for multi site installs.  Most of these plugins only do one thing, but a few others add major updates to WordPress.

### Included Plugins

* Category Excluder - Allows a user to select the categories they wish to exclude and where they would like to excluded the categories from (main page, feed, archives). See: Tools -> Webmaster Tools
* Contact Info - Adds Custome User Contact Methods. Adds Facebook URL, Google Talk, & Twitter ID. Removes AIM & Jabber. See: Users -> Your Profile
* Enable Update Services - Enables the Update Service for network installs of WordPress. See: Settings -> Writing
* Favicon - Provides favicon support for WordPress network installs. If a user dosen't have a favicon.ico or favicon.png file in their files directory, it will use their Gravatar image, if they don't have that, it will use the site's default.
* Feedburner - Redirects all sites feeds to the entred Feedburner feed. See: Settings -> Reading
* Picasa Captioner - Fix for WordPress to read Picasa Captions from EXIF info properly.
* Proper Network Activation - Use the network activation feature of WP MultiSite without problems.
* Sitemap Generator - Automatic generate standard XML sitemap (http://example.com/sitemap.xml) that supports the protocol including Google, Yahoo, MSN, Ask.com, and others. No files stored on your disk, the sitemap.xml file is generate as needed, like your feeds.
* Sub Pages widget - Show only the sub pages, if the current page has sub pages.
* Time Since - Adds a Time Since shotcode "[ts date'1980-06-19']" that will display the years since the date provided.
* Typekit Fonts - Provides Typekit fonts to a website by adding the needed java code to the pages. See Appearance -> Fonts
* Webmaster Tools - Provides Webmaster site verification scripts for Google, Yahoo, & Bing. Plugin also provides Google Analytics Tracking Script for registered sites. See Tools -> Webmaster Tools

## Rebuilding the POT file

    xgettext --keyword=__ --keyword=_e  --from-code=php --language=php *.php --package-version=1.o --package-name=mdr-network --output=languages/mdr-network.pot

## License
These Plugins are free software; you may redistribute and/or modify them under the terms of the GNU General Public License version 2 as published by the Free Software Foundation (http://www.fsf.org/).

                  GNU GENERAL PUBLIC LICENSE
                     Version 2, June 1991
    
    Copyright (C) 1989, 1991 Free Software Foundation, Inc.,
    51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA
    Everyone is permitted to copy and distribute verbatim copies
    of this license document, but changing it is not allowed.

## Known Bugs
* https://github.com/mattrude/mdr-network/issues
