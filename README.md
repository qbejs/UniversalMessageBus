# Universal Message Bus
#### A flexible, framework-agnostic Message Bus that can be easily integrated with popular frameworks such as Symfony or Laravel. It provides features like environment-specific configurations, message validation, serialization/deserialization, retry mechanisms, event listeners, middleware, and more.

## Integration

### Symfony

1. **Configuration Environment**

    - Register `SymfonyConfigEnv` as a service in `services.yaml`:

      ```yaml
      services:
          MessageBus\Implementations\SymfonyConfigEnv:
              arguments:
                  $params: '@parameter_bag'
      ```

    - Use `SymfonyConfigEnv` to initialize `ConfigurationManager`:

      ```php
      use MessageBus\ConfigurationManager;
      use MessageBus\Implementations\SymfonyConfigEnv;
 
      $configManager = new ConfigurationManager([new SymfonyConfigEnv($params)]);
      $configManager->loadConfiguration('symfony');
      ```

2. **MessageBus Service**

    - Register `MessageBus` and its dependencies in `services.yaml`:

      ```yaml
      services:
          MessageBus\MessageBus:
              arguments:
                  $config: '@MessageBus\Implementations\SymfonyConfigEnv'
                  $middlewareManager: '@MessageBus\MiddlewareManager'
                  $retryDispatcher: '@MessageBus\RetryDispatcher'
          MessageBus\MiddlewareManager: ~
          MessageBus\RetryDispatcher:
              arguments:
                  $adapter: '@YourQueueAdapterService'
      ```

      Replace `YourQueueAdapterService` with the service ID of your chosen queue adapter.

### Laravel

1. **Configuration Environment**

    - In the `AppServiceProvider.php` file, within the `register` method, add:

      ```php
      $this->app->singleton(MessageBus\Interfaces\ConfigEnvInterface::class, MessageBus\Implementations\LaravelConfigEnv::class);
      ```

    - Use `LaravelConfigEnv` to initialize `ConfigurationManager`:

      ```php
      use MessageBus\ConfigurationManager;
      use MessageBus\Implementations\LaravelConfigEnv;
 
      $configManager = new ConfigurationManager([new LaravelConfigEnv()]);
      $configManager->loadConfiguration('laravel');
      ```

2. **MessageBus Service**

    - Register `MessageBus` and its dependencies in `AppServiceProvider.php`:

      ```php
      $this->app->singleton(MessageBus\MessageBus::class, function ($app) {
          $config = $app->make(MessageBus\Interfaces\ConfigEnvInterface::class);
          $middlewareManager = $app->make(MessageBus\MiddlewareManager::class);
          $retryDispatcher = $app->make(MessageBus\RetryDispatcher::class);
          return new MessageBus\MessageBus($config, $middlewareManager, $retryDispatcher);
      });
 
      $this->app->singleton(MessageBus\MiddlewareManager::class, function ($app) {
          return new MessageBus\MiddlewareManager();
      });
 
      $this->app->singleton(MessageBus\RetryDispatcher::class, function ($app) {
          $adapter = $app->make(YourQueueAdapterClass::class);
          return new MessageBus\RetryDispatcher($adapter);
      });
      ```

      Replace `YourQueueAdapterClass` with the class name of your chosen queue adapter.

## Usage

### Sending and Receiving Messages

```php
$message = new YourMessageClass();
$response = $messageBus->dispatch($message);
```

### Middleware

```php
$middleware = new YourMiddlewareClass();
$messageBus->addMiddleware($middleware);
```

### Event Listeners

```php
$listener = new YourEventListenerClass();
$messageBus->addEventListener(MessageBus::BEFORE_DISPATCH, $listener);
```

### Retry Mechanisms

```php
$messageBus->withRetry($message);
```

### Serialization and Deserialization

```php
$serializer = new JsonSerializer(); // or XmlSerializer, etc.
$messageBus->setSerializer($serializer);
```

### Validation

```php
$validator = new YourValidatorClass();
$messageBus->setValidator($validator);
```

### Environment-specific Configuration

```php
$configManager->loadConfiguration('production'); // or 'development', 'staging', etc.
```

## Expansion

If you wish to expand `MessageBus` within your application:

1. Create a class implementing the relevant interface, e.g., `ConfigEnvInterface`, `SerializerInterface`, etc.
2. Register your class in the appropriate place, like `ConfigurationManager`, `MessageBus`, etc.
3. Use your class in the appropriate place in the application.
