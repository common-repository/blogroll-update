<?php
/*
Plugin Name: Blogroll Update
Plugin URI: http://hitormiss.org/projects/blogroll_update
Description: Check weblogs.com and pingomatic.com for updates to your WP Blogroll
Version: 0.1
Author: Matt Kingston
Author URI: http://www.hitormiss.org/
*/
	# grab timestamps of the last time weblogs.com & pingomatic.com were checked for updates
	$weblogscom_timestamp = get_option('last_Weblogs_com_check_timestamp');
	$pingomatic_timestamp = get_option('last_Pingomatic_check_timestamp');

	# if there is no pingomatic.com timestamp, create it with the current timestamp
	if (!$pingomatic_timestamp)
	{
		add_option('last_Pingomatic_check_timestamp', time(), 'timestamp of last pingomatic.com check for Blogroll-Update plugin'); 
	}
	# check to see if it's been more than 1 hour since pingomatic.com was checked
	elseif ((time() - $pingomatic_timestamp) > (60*15)) // 60*60 = 1 hours
	{
		update_option('last_Pingomatic_check_timestamp', time());
		require_once( ABSPATH . 'wp-admin/update-links.php');
	}

	# if there is no weblogs.com timestamp, create it with the current timestamp
	if (!$weblogscom_timestamp)
	{
		add_option('last_Weblogs_com_check_timestamp', time(), 'timestamp of last weblogs.com check for Blogroll-Update plugin'); 
	}
	# check to see if it's been more than 5 minutes since weblogs.com was checked
	elseif ((time() - $weblogscom_timestamp) > (60*5-2)) // 60*5-2 = 5 minutes - 2 seconds
	{
		update_option('last_Weblogs_com_check_timestamp', time());

		# create an array of stripped-down Blogroll urls
		$link_urls = $wpdb->get_col("SELECT link_url FROM $wpdb->links");
		if (!$link_urls) die ('You should disable the blogroll_update plugin because you have no links in your blogroll');
		for ($x = 0; $x < count($link_urls); $x++)
		{
			$link_urls[$x] = blogroll_update_strip_url($link_urls[$x]);
		}

		# load the list of weblogs.com changes from the last 5 minutes
		$changes = file("http://www.weblogs.com/shortChanges.xml");
		if ($changes)
		{
			foreach ($changes as $line) 
			{
				# find the base timestamp of the changes feed
				if (preg_match("/<weblogUpdates/", $line))
				{ 
					preg_match("/updated=\"(.+?)\"[>\s]/i", $line, $matches); 
					$timestamp = strtotime($matches[1]);
					#print "<p>$timestamp</p>";
				}

				if (preg_match("/<weblog name/", $line))
				{ 
					preg_match("/url=\"(.+?)\" when=\"([0-9]+)/i", $line, $matches);
					$url = $matches[1]; $when = $matches[2];
					$key = blogroll_update_strip_url($url); 
	
					# check to see if current updated blog is on blogroll
					if (in_array($key, $link_urls)) 
					{
						$date = date("Y-m-d H:i:s", $timestamp-$when);
						$result = $wpdb->query("update $wpdb->links set link_updated='$date' 
									WHERE link_url LIKE '%$key%' AND 
									link_updated < '$date' ");
						#print "$result, $key, $when\n";
					}
				}
			}
		}
	}

function blogroll_update_strip_url($url)
{
	$url = strtolower($url);
	$search = array ("'www\.'", "'(?:index|default)\.[a-z]{2,}'i", "'/$'");
	$replace = array ("", "", "");
	$url = preg_replace($search, $replace, $url);
	return $url;
}

?>