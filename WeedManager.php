<?php
namespace Micjohnson\WeedPhpBundle;

class WeedManager
{
	protected $storage;
	
	public function __construct($storage)
	{
		$this->storage = $storage;
	}
	
	public function store($entity)
	{
		$dataArray = $entity->getData();
		if(!is_array($dataArray)) {
		    $dataArray = array( $dataArray );
		}
		$versions = array();
		foreach($dataArray as $name=>$data) {
		    $versions[] = $name;
		}
		$response = $this->storage->store($entity);
		$entity->setVersions($versions);
	}
	
	public function retrieve($entity, $version = 0)
	{
		return $this->storage->retrieve($entity, $version);
	}
	
	public function delete($entity)
	{
		$response = $this->storage->delete($entity);
		$entity->setData(null);
		$entity->setVersions(null);
		$entity->setFileId(null);
	}
}