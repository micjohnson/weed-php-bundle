weed-php-bundle
===============

Weed-FS integration for your Symfony 2 application using WeedPhp

About Weed-FS
===============

Weed-FS Homepage: [weedfs](http://code.google.com/p/weed-fs/)

Weed-FS is a simple and highly scalable distributed file system. There are two objectives:

 1) to store billions of files!  
 2) to serve the files fast! 

Instead of supporting full POSIX file system semantics, Weed-FS choose to implement only a key~file mapping. Similar to the word "NoSQL", you can call it as "NoFS".

Instead of managing all file metadata in a central master, Weed-FS choose to manages file volumes in the central master, and let volume servers manage files and the metadata. This relieves concurrency pressure from the central master and spreads file metadata into volume servers' memories, allowing faster file access with just one disk read operation!

Weed-FS models after Facebook's [Haystack design paper.](http://www.usenix.org/event/osdi10/tech/full_papers/Beaver.pdf)

Weed-FS costs only 40 bytes disk storage for each file's metadata. It is so simple with O(1) disk read that you are welcome to challenge the performance with your actual use cases.

Testing
===============

TODO

Status
===============

Ready for testing / in development

Documentation
===============

This bundle is meant to be tested only, it will be updated rapidly.

### Step 1: Install using composer

Add to your composer.json
```
"require": {
    "micjohnson/weed-php-bundle": "dev-master"
}
```

Install through composer.phar
```
php composer.phar update micjohnson/weed-php-bundle
```

### Step 2: Add to AppKernel.php
Enable the bundle in the kernel
```
    public function registerBundles()
    {
        $bundles = array(
            // ...
			new Micjohnson\WeedPhpBundle\MicjohnsonWeedPhpBundle(),
        );
    }
```

### Step 3: Configure the address and port of your master weed-fs server
Note: This defaults to localhost:9333, you only need to override it if its not default.
```
// app/config/parameters.yml
parameters:
    weed_php.master.address: "localhost:9333"
```

### Step 4: Extend the base Image Entity
If you are doing images, you might want a single table for all files
```
<?php
namespace Test\WeedPhpBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Micjohnson\WeedPhpBundle\Entity\WeedStorableFile;

/**
 * @ORM\Entity()
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\DiscriminatorMap({"baseimage" = "Test\WeedPhpBundle\Entity\BaseImage", "webimage" = "Test\WeedPhpBundle\Entity\WebImage"})
 * @ORM\Table(name="test_weed_php")
 *
 */
class BaseImage extends WeedStorableFile
{
    /**
     *
     * id
     * @var unknown_type
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
}
```

Heres the entity you would use
```
<?php
namespace Test\WeedPhpBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Test\WeedPhpBundle\Entity\BaseImage;

/**
 * @ORM\Entity()
 */
class WebImage extends BaseImage
{
    
}
```

### Step 5: Set your entities data property with your files raw data
Data is not persisted, and is only temporary. So be sure to store right after setting the data property.
```
$image = new WebImage();
$image->setData($rawImage);
```

### Step 6: Use WeedManager to store
Use the weedphp's manager to store
```
$weedManager = $this->get('weed_php.manager');
$weedManager->store($image);

//persist to save location data in database
$entityManager->persist($image);
$entityManager->flush();
```

### Step 7: Retrieve and delete files
The manager also retrieves and deletes
```
$weedManager = $this->get('weed_php.manager');

$imageRawData = $weedManager->retrieve($image);

$weedManager->delete($image);

// file is gone, you can get rid fo the entity
$entityManager->remove($image);
$entityManager->flush();
```