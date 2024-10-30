=== Blogroll Update ===

Tags: blogroll, update
Contributors: MattKingston

The Blogroll Update checks pingomatic.com (every 15 minutes) and weblogs.com (every 5 minutes, using the shortChanges.xml feed).  If a link from your WP Blogroll appears, the plugin marks it as "updated," which will be reflected in the output of your blogroll if you have enabled the marking of recently updated links.

== Installation ==

1. Save blogroll_update.php in your /wp-content/plugins folder.
2. Make sure that "Track Links' Update Times" is checked on the Options > Miscellaneous > page.
3. Active the 'Blogroll Update' plugin on the Plugins administration page.


== Frequently Asked Questions == 

= Do I really need to use this plugin? =

Not really, but it's helpful if you'd like to see which links on your blogroll have recently updated when used with the get_links template tag or a plugin that displays your blogroll, like my Blogroll-Favicons plugin (http://www.hitormiss.org/projects/blogroll-update).


= Why would I use this plugin as opposed to the built-in links-update-xml.php or update_links.php? =

This plug-in combines what each of those files does (checking weblogs.com and pingomatic.com).  Right now more people are pinging weblogs.com, but over time they will hopefully shift to pingomatic.com.  Also, this plugin checks the two services on a regular basis without having to use an external file or a cron job.

