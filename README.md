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