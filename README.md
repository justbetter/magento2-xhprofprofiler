# Xhprof Profiler for Magento 2

## Overview

This module integrates Xhprof profiling capabilities into your Magento 2 application. It provides an easy way to profile and analyze your application's performance by tracking and storing profiling data.

## Requirements

- Magento 2.4.7 or higher
- Xhprof PHP extension
- Compatible with XHGui
- Compatible with Buggregator

## Installation

1. **Install via composer:**

   ```bash
   composer require justbetter/magento2-xhprof-profiler
   ```

2. **Enable the module:**

   ```bash
   bin/magento module:enable JustBetter_XhprofProfiler
   ```

3. **Run setup upgrade and di compile:**

   ```bash
   bin/magento setup:upgrade
   bin/magento setup:di:compile
   ```

## Configuration for Buggregator

By default this module uses XHGui for processing the profiling data. We can use the Buggregator driver by overriding the default driver via xml:
```
<type name="JustBetter\XhprofProfiler\Model\Profiler\XhprofProfiler">
    <arguments>
        <argument name="driver" xsi:type="object">JustBetter\XhprofProfiler\Model\Profiler\Driver\Buggregator</argument>
    </arguments>
</type>
```

Configure the module by adding the following configuration to your `app/etc/env.php` file:

```php
return [
    // ... other configurations ...
    'xhprofprofiler' => [
        'app_name' => 'Magento 247',
        'endpoint' => 'http://exciting_chatelet.orb.local/profiler/store'
    ],
];
```

- **app_name**: The name of your application.
- **endpoint**: The endpoint where the profiling data will be stored.

## Compatibility

This module is compatible with [XHGui](https://github.com/perftools/xhgui) and [Buggregator](https://buggregator.dev/). These are graphical interfaces for viewing XHProf profiling data.

### To integrate with XHGui:

1. Follow the installation guide of XHGui at [XHGui](https://github.com/perftools/xhgui)
2. By default we use the default xhprof profiler and the results are getting uploaded to XHGui.
3. Update default configuration via di.xml, check [config.default.php](https://github.com/perftools/xhgui/blob/0.23.x/config/config.default.php) for the possible options. 

#### Default XHGui configuration 
You can pass custom configuration to the XHGui driver via the arguments like the default configuration.
```
<type name="JustBetter\XhprofProfiler\Model\Profiler\Driver\XHGui">
    <arguments>
        <argument name="config" xsi:type="array">
            <item name="profiler" xsi:type="string">xhprof</item>
            <item name="save.handler" xsi:type="string">upload</item>
            <item name="save.handler.upload" xsi:type="array">
                <item name="url" xsi:type="string">http://xhgui.xhgui.orb.local/run/import</item>
                <item name="timeout" xsi:type="number">3</item>
                <item name="token" xsi:type="string">token</item>
            </item>
        </argument>
    </arguments>
</type>

```

### To integrate with Buggregator:

1. Ensure Buggregator is installed and configured in your environment.
2. Configure the endpoint in `app/etc/env.php` to point to Buggregator's profiling data endpoint.

Example configuration:

```php
return [
    // ... other configurations ...
    'xhprofprofiler' => [
        'app_name' => 'Magento 247',
        'endpoint' => 'http://your_buggregator_instance/profiler/store'
    ],
];
```

## Usage

The profiling is automatically enabled for all requests. The module uses the `AppInterfacePlugin` to start and terminate the profiler around each request.

### Key Classes and Methods

- **`XhprofProfiler`**
    - **Methods:**
        - `__construct()`: Initializes the profiler with the given driver and optional tags.
        - `handle()`: Starts the profiler.
        - `terminate()`: Ends the profiler and stores the profiling data.

- **`AppInterfacePlugin`**
    - **Methods:**
        - `aroundLaunch()`: Wraps around the application launch to start and stop the profiler.
      
## License

This project is licensed under the MIT License - see the LICENSE file for details.

## Contributing

1. Fork the repository.
2. Create your feature branch (`git checkout -b feature/fooBar`).
3. Commit your changes (`git commit -am 'Add some fooBar'`).
4. Push to the branch (`git push origin feature/fooBar`).
5. Create a new Pull Request.

## Contact

If you have any questions or need further assistance, please contact [robin@justbetter.nl].

---

By following this README, you should be able to integrate and use the Xhprof Profiler in your Magento 2 application effectively. Happy profiling!
