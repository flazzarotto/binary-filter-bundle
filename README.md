README
======

This Symfony3 bundle use `liip/imagine` to allow easy php-side image filter. 
 
## Set up

1. Run `composer require flazzarotto/binary-filter dev-master`

2. Modify you AppKernel.php:

   ```php
   $bundles = [
     // bunch of other bundles
     new Flazzarotto\BinaryFilterBundle\BinaryFilterBundle(), // <= add this line
   ];
   ```

3. Configure your filters the same way you do with liip, but using our binary image loader as data loader.
For example:

    ```yml
    # config.yml
    liip_imagine :
        # your filter sets are defined here
        filter_sets :
            # use the default cache configuration
            cache : ~
    
            my_filter:
                data_loader: binary_image_data_loader
                filters:
                    thumbnail:
                        size: [1920, 1080]
                        mode: outbound
    ```

HOW TO USE
==========
The main goal of this package is to allow you to resize, generate thumbnails on the fly, in controllers, commands,
services... You can provide both binary data and filepaths to the service.

Example of use:

```php
$filter = $this->get('image.back_filter'); // the BinaryFilter service

$filter ->setDefaultFilter('my_filter') // filter as defined in your config.yml - optional

        ->loadBinary($data,$outputFile) // $data as binary, $outputFile as path relative to directory - return a BinaryFilter object
        // OR
        ->loadFile($path) // provide absolute path to your input image - return a BinaryFilter object

        ->applyFilter($filter) // you can override default filter - optional if default filter has been given
        
        ->getMimeType(); // optional; useful to determine extension or for direct download
        
        ->outputFile(true); // save filtered image to output file; set parameter to true to allow overriding if file exists
        // OR
        ->getFilteredBinary(); // returns picture as binary data
```