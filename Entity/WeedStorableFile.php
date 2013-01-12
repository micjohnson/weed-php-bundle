<?php
namespace Micjohnson\WeedPhpBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\MappedSuperclass
 *
 */
class WeedStorableFile
{
	/**
	 * @ORM\Column(name="file_id", type="string", length=80, nullable=true)
	 * @var string
	 */
	protected $fileId;

	/**
	 * array("big"=>"A bigger file", "small"=>"petit")
	 * "123JFI@#FIJas0d0"
	 *
	 * @var mixed raw file data
	 */
	protected $data;

	/**
	 * @ORM\Column(name="versions", type="array", nullable=true)
	 * @var array versions of data stored
	 */
	protected $versions = array();

	/**
	 * @ORM\Column(name="replication_scheme", type="string", length=10, nullable=true)
	 * @var string
	 */
	protected $replicationScheme;

	public function getReplicationScheme()
	{
		return $this->replicationScheme;
	}

	public function setReplicationScheme($scheme)
	{
		$this->replicationScheme = $scheme;
	}

	public function setFileId($fid)
	{
		$this->fileId = $fid;
	}

	public function getFileId()
	{
		return $this->fileId;
	}

	public function setData($data)
	{
		$this->data = $data;
	}

	public function getData()
	{
		return $this->data;
	}

	public function getVersionOffset($versionName)
	{
		foreach($this->versions as $offset=>$version) {
			if($versionName == $version) {
				return $offset;
			}
		}
		return 0; // default to first
	}

	public function setVersions($versions)
	{
		$this->versions = $versions;
	}

	public function getVersions()
	{
		return $this->versions;
	}

	public function hasVersion($versionName)
	{
		foreach($this->versions as $version) {
			if($versionName == $version) {
				return true;
			}
		}
		return false;
	}
}