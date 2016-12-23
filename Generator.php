<?php

namespace Jobs\Seo;

use Models\Event,
	Models\Location,
	Library\Utils\SlugUri,
	Library\Sitemap\Sitemap,
	Models\Event\Grid\Search\EventSearch;

class Generator
{
	public $_di;
	public $_smConfig;
	
	
	public function __construct(\Phalcon\DI $dependencyInjector)
	{
		$this -> _di = $dependencyInjector;
		$this -> _smConfig = $this -> _di -> get('config') -> sitemap;
	}
	
	
	public function run()
	{
		$generator = new Sitemap($this -> _smConfig -> domain, $this -> _smConfig -> sitemapPath);
		$generator -> setPathIndex($this -> _smConfig -> indexPath);
		$generator -> setUrlSitemap($this -> _smConfig -> sitemapUrl);
		
		$sourceMethod = 'getDataFrom' . ucfirst($this -> _smConfig -> dataSource);

		$locations = Location::find();
		foreach($locations as $loc)
		{
			$result = $this -> $sourceMethod($loc -> id);
			if ($result) {
				foreach ($result['data'] as $id => $e) {
					$url = SlugUri::slug($e -> name) . '-' . $e -> id;
					$generator -> addItem($url);
				}
			}
		}
		$generator -> createSitemapIndex();
		$list = $generator -> getSitemapsList();
		$index = $generator -> getSitemapsIndex();
		
		print_r($list); 
		print_r($index);
		
		if (!empty($list)) shell_exec($this -> _smConfig -> shell_path);
		
		print_r("\n\rready\n\r"); 
		die();	
	}
	
	
	private function getDataFromIndex($locationId)
	{
		$result = false;
		
		$eventGrid = new EventSearch(['searchLocationField' => $locationId], $this -> _di, null, ['adapter' => 'dbMaster']);
		if ($this -> _smConfig -> limitPerLocation) $eventGrid -> setLimit(100);
		$data = $eventGrid -> getData();
		
		if($data) $result = $data['data'];
		
		return $result;
	}

	
	private function getDataFromDatabase($locationId)
	{
		$source = (new Event()) -> setShardByCriteria($locationId);
		$result = $source::find(['location_id = ' . $locationId]);
		
		return $result;
	}
}