# Inspector | Code Execution Monitoring tool

Provides a wrapper for Inspector's PHP monitoring Library. inspector-apm/inspector-php

## Quick Start

1. Install with Composer: `> composer require inspector-apm/inspector-codeigniter`
2. Create a new config class and copy the provided class from below into it.

`Inspector CodeIgniter` provides a simple wrapper around the inspector PHP monitor Library that you can use for your
CodeIgniter4 applications. Its made for ease of use and can be configured to 'auto inspect' your code with no extra code
from you. Just install, configure and it will do all the work for you.

Its also very flexible allowing you to override the auto inspect, so you have more power over your inspection points. 
You can use it in your Controllers, Models, Events, Libraries, and custom classes. 
Any code that has access to CI4's services can make use of the library.

## Installation

Install easily via Composer to take advantage of CodeIgniter 4's autoloading capabilities
and always be up-to-date:
* `> composer require inspector-apm/inspector-codeigniter`

Or, install manually by downloading the source files and adding the directory to
`app/Config/Autoload.php`.

## Setup

In order to start using the integration library, you will need to create a config class for it.
`> ./spark make:config Inspector

```php
<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Inspector extends BaseConfig
{
    /**
     * set to true if you want all your controller methods to be 'auto-inspected'
     * set to false to set your own inspection points - provides more flexibility
     *
     * @var bool
     */
    public $AutoInspect  = true;
    
    /**
     * application ingestion key, you can find this on your inspector dashboard
     *
     * @var string
     */
    public $IngestionKey = 'YOUR_INGESTION_KEY';
    
    /**
     * @var bool
     */
    public $Enable = true;
    
    /**
     * Remote endpoint to send data.
     *
     * @var string
     */
    public $URL = 'https://ingest.inspector.dev';
    
    /**
     * @var string
     */
    public $Transport = 'async';
    
    /**
     * Transport options.
     *
     * @var array
     */
    public $Options = [];
    
    /**
     * Max numbers of items to collect in a single session.
     *
     * @var int
     */
    public $MaxItems = 100;
}
```

## Usage

To use the inspector library integration use the `inspector` service. 

```php
$inspectorInstance = service('inspector');
```

With AutoInspect set to true, you don't need to do anything else, your application will start being inspected
automatically, this is made possible by the use of CI4 events functionality in the `post_controller_constructor`
the code will start a segment, providing the controller as the title and the method name as the label.
Then in the `post_system` it will end the segment, which means from the start of the incoming request till result
delivery, your code paths will be 'tracked' and the results submitted to inspector.dev. And that's it.

You may however need finer grained control over your code points and maybe need to access other more powerful
inspector functionality, and this is where the service comes in. Here we present just a few useful methods,
check the inspector documentation for more methods and features.

You can add a segment from anywhere in your code (assuming this is in your controller method getUsers):

```php
/* gets JSON payload of $limit users */
public function getUsers(int $limit)
{
  return $inspectorInstance->addSegment(function() {
    $userModel = new UserModel();
    $users = $userModel->findAll($limit);
    $this->response->setStatusCode(200, 'OK')->setJSON($users, true)->send();
  }, 'getUsers', 'Get Users');
}
```

You can report an exception from anywhere in your code as well (assuming this is your model method, where you validate stuff).
```php
/* validate the user has the proper age set */
public function validateUserAge(): bool
{
  try {
    if($this->UserAge < 13) {
      throw new \UserException\AgeNotAppropriate('Cannot register user, minimum age requirement not met.');
    }
  } catch (\UserException\AgeNotAppropriate $e) {
    $inspectorInstance->reportException($e);
    /* Your exception handling code... */
  }
}
```

### Using the Helper

The helper provides a shortcut to the using the service. It must first be loaded using the `helper()` method
or telling your BaseController to always load it.

```php
helper('inspector');

/* get an instance of inspector */
$inspectorInstance = inspector();

/* add a segment through the helper */
inspector(function () {
  /* run your code here... */
  $asyncData = $this->getAsyncData('https://data.mysite.io');
  return $this->startDataSyncJob($asyncData);
}, 'data-load', 'Data Load Flow');

/* add a segment through the instance */
$inspectorInstance->addSegment(function () {
  /* run your code here... */
  try {
    $asyncData = $this->getAsyncData('https://data.mysite.io');
    return $this->startDataSyncJob($asyncData);
  } catch(\DataException\DataLoadException $e) {
    $inspectorInstance->reportException($e);
  }
}, 'data-load', 'Data Load Flow');

/* set an exception */
$inspectorInstance->reportException(new \Exception('Model Setup Error'));
```

> Note: Due to the shorthand nature of the helper function it can only add a segment or return a service instance.

## More Info
For more info on inspector's monitoring library and the developer documentation, please visit [![Documentation](https://docs.inspector.dev)](https://docs.inspector.dev)
