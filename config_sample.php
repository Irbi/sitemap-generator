<?php

$sitemap = [
	/* site URL in sitemaps */
	'domain' => 'http://yourdomain.com',
		 					
	/* sitemaps files would be placed here */						 
	'sitemapPath' => '/var/www/ProjectName/sitemap/maps',

	/* sitemaps index */	
	'indexPath' => '/var/www/ProjectName/sitemap',				
		
	/* sitemap URL =) */	
	'sitemapUrl' => 'http://yourdomain.com/sitemap',
		
	/* data source. 'index' or 'database' */
	'dataSource' => 'index',	

	/* number of events per location */	
	'limitPerLocation' => false,
		
	/* path to script for copying to another domain. 
	 * If you don't need this leave false
	 */	
	'shell_path' => false												
];
