# PHP Library for api.govinfo.gov

[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)

This alpha stage package provides a very simple way to access [api.govinfo.gov](https://api.govinfo.gov/docs/)

## Install

You can install this package via composer.

```bash
composer require cnizzardini/gov-info
```

## Usage

Retrieve an index of all collections available

```php
use GovInfo\Api;
use GovInfo\Collection;

$api = new Api(
    new \GuzzleHttp\Client(), 
    'DEMO_KEY'
);
$collection = new Collection($api);
$result = $collection->index();
```

After running this code `$result` will contain a non-truncated version of collections:

```
Array
(
    [collections] => Array
        [
            [0] => Array
                [
                    [collectionCode] => USCOURTS
                    [collectionName] => United States Courts Opinions
                    [packageCount] => 1134933
                    [granuleCount] => 2525240
                ]
        ...
)

```

Retrieve all packages in a collection

```php
use GovInfo\Api;
use GovInfo\Collection;
use GovInfo\Requestor\CollectionRequestor;

$api = new Api(
    new \GuzzleHttp\Client(), 
    'DEMO_KEY'
);
$collection = new Collection($api);
$requestor = new CollectionRequestor();

$requestor
    ->setStrCollectionCode('BILLS')
    ->setObjStartDate(new DateTime('2019-01-01'));

$result = $collection->item($requestor);

```

After running this code `$result` will contain a non-truncated version of packages:

```
Array
(
    [count] => 202767
    [message] => 
    [nextPage] => https://api.govinfo.gov/collections/BILLS/2018-01-01T00:00:00Z/?offset=100&pageSize=100
    [previousPage] => 
    [packages] => Array
        [
            [0] => Array
                [
                    [packageId] => BILLS-115hr2740rfs
                    [lastModified] => 2018-11-16T00:33:17Z
                    [packageLink] => https://api.govinfo.gov/packages/BILLS-115hr2740rfs/summary
                    [docClass] => hr
                    [title] => Rabbi Michoel Ber Weissmandl Congressional Gold Medal Act of 2017
                ]
        ...
```

Retrieve a specific package in a collection. 

```php
use GovInfo\Api;
use GovInfo\Collection;
use GovInfo\Requestor\CollectionRequestor;

$api = new Api(
    new \GuzzleHttp\Client(), 
    'DEMO_KEY'
);
$collection = new Collection($api);
$requestor = new CollectionRequestor();

$requestor
    ->setStrCollectionCode('BILLS')
    ->setObjStartDate(new \DateTime('2018-01-01 12:00:00'))
    ->setObjEndDate(new \DateTime('2018-02-01 12:00:00'))
    ->setStrDocClass('hr')
    ->setStrPackageId('BILLS-115hr4033rfs')
    ->setStrTitle('Geologic Mapping Act');

$result = $collection->item($requestor);
```

After running this code `$result` will contain the requested package

```
Array
(
    [count] => 202767
    [message] => 
    [nextPage] => https://api.govinfo.gov/collections/BILLS/2018-01-01T00:00:00Z/?offset=100&pageSize=100
    [previousPage] => 
    [packages] => Array
        [
            [2] => Array
                [
                    [packageId] => BILLS-115hr4033rfs
                    [lastModified] => 2018-11-16T00:33:00Z
                    [packageLink] => https://api.govinfo.gov/packages/BILLS-115hr4033rfs/summary
                    [docClass] => hr
                    [title] => National Geologic Mapping Act Reauthorization Act
                ]
        ]
)
```

Retrieve a summary of a package.

```php
use GovInfo\Package;
use GovInfo\Requestor\PackageRequestor;

$package = new Package($api);
$requestor = new PackageRequestor();

$result = $package->summary($requestor->setStrPackageId('BILLS-115hr4033rfs'));
```

After running this code `$result` will contain a non-truncated version of the package summary

```
Array
(
    [title] => An Act To reauthorize the National Geologic Mapping Act of 1992.
    [shortTitle] => Array
        [
            [0] => Array
                [
                    [type] => measure
                    [title] => National Geologic Mapping Act Reauthorization Act
                ]

        ]
    ...
```

Retrieve a package as a specified content-type

```php
use GovInfo\Package;
use GovInfo\Requestor\PackageRequestor;

$package = new Package($api);
$requestor = new PackageRequestor();
$requestor
    ->setStrPackageId('BILLS-115hr4033rfs')
    ->setStrContentType('xml');

$result = $package->contentType($requestor);
```

After running this code `$result` will be an instance of GuzzleHttp\Psr7\Response

## Console 

There is a minimalist console application that *can* be used, but its not designed for production use. 
I built this so I could easily regression test the library against the production API. Each command 
will prompt you for an API key. To avoid this prompt you can create a local `apiKey.txt` file with your 
API key in there.

```shell
# displays collections
php console.php collection:index

# display packages for a given collection
 
# outputs table
php console.php collection:packages

# writes to csv
php console.php collection:packages --csv

# writes to csv in a specific folder
php console.php collection:packages --csv --csvPath=/home/username/Desktop

# retrieves a package summary
php console.php package:summary
```

## Testing

```bash
vendor/bin/phpunit
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.