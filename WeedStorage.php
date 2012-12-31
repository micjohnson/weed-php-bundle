<?php
namespace Micjohnson\WeedPhpBundle;

use InvalidArgumentException;

class WeedStorage
{
	protected $weedPhp;
	
	public function __construct($weedPhp)
	{
		$this->weedPhp = $weedPhp;
	}
	
	/**
	 * return a random server
	 * @param unknown $volumeId
	 */
	public function randomLookup($volumeId)
	{
		$lookup = $this->weedPhp->lookup($volumeId);
		$lookup = json_decode($lookup, true);
		$locs = $lookup['locations'];
		$count = count($locs);
		$rand = rand(0, $count-1);
		return $locs[$rand]['publicUrl'];
	}

	public function store($entity)
	{
		$dataArray = $entity->getData();
		if(!is_array($dataArray)) {
		    $dataArray = array( $dataArray );
		}
		$count = count($dataArray);
		$files = array();
		foreach($dataArray as $data) {
		    $files[] = $data;
		}
		$replication = $entity->getReplicationScheme();
		$assignResponse = $this->weedPhp->assign($count, $replication);
		$assignResponse = json_decode($assignResponse, true);
		$volumeAddress = $assignResponse['publicUrl'];
		$fid = $assignResponse['fid'];
		$entity->setFileId($fid);
		$response = $this->weedPhp->storeMultiple($volumeAddress, $fid, $files);
		return $response;
	}

	public function retrieve($entity, $version)
	{
		$fileId = $entity->getFileId();
		if($version !== 0) {
		    if(!$entity->hasVersion($version)) {
		        throw new InvalidArgumentException('That version does not exist');
		    }
		    $fileId .= '_' . $entity->getVersionOffset($version);
		}
		$volumeId = explode(',', $entity->getFileId());
		$volumeId = $volumeId[0];
		$serverAddress = $this->randomLookup($volumeId);
		
		return $this->weedPhp->retrieve($serverAddress, $fileId);
	}
	public function delete($entity)
	{
		$fileId = $entity->getFileId();
	    $volumeId = explode(',', $entity->getFileId());
		$volumeId = $volumeId[0];
		$serverAddress = $this->randomLookup($volumeId);
		foreach($entity->getVersions() as $offset=>$name) {
		    $this->weedPhp->delete($serverAddress, $fileId);
		    $fileId = $entity->getFileId() . '_' . ($offset+1);
		}
	}
}