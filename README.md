# Speech
## JSON-RPC 2.0 for PHP
[![Build Status](https://travis-ci.org/zored/speech.svg?branch=master)](https://travis-ci.org/zored/speech)
[![Latest Stable Version](https://poser.pugx.org/zored/speech/v/stable)](https://packagist.org/packages/zored/speech)
Bundle for JSON-RPC 2.0.
You can use as **standalone library**. Or with **Symfony**.

## Features
- Validation. Based on [Symfony Validator component](https://github.com/symfony/validator).
- Deserialization. Based on [JMS](https://github.com/schmittjoh/serializer).
- Customization. Change your rules for **[JSON-RPC 2.0](http://json-rpc.org/wiki/specification)**.

## Use
### Symfony
- Install package: `composer require zored/speech`.
- Add bundle `new Zored\SpeechBundle\ZoredSpeechBundle()` to your Symfony kernel `app/AppKernel.php`.
- Add route to default endpoint: `routing.yml`:
    ```yaml
    json_rpc:
        defaults: { _controller: ZoredSpeechBundle:Default:jsonRpc }
        methods: [POST] # Or GET for Zored RPC.
        path: /json-rpc
    ```
- Add service with your JSON-RPC methods in `services.yml`:
    ```yaml
    services:
        your.service:
            # Replace with your own class:
            class: 'Zored\SpeechBundle\Test\JsonRpc\Greeter'
    ```
- Allow this service to run in default controller via `parameters.yml`:
    ```yaml
    parameters:
      zored.speech.controller.context.service_ids: ['your.service']
    ```
- Clear caches.
- Test:
    ```bash
    curl http://example.com/json-rpc/ \
        --data '{"id":"1", "method":"your.service:greet", "params":{"person":{"name":"bob","age":19}},"jsonrpc":"2.0"}'
    ```

### Standalone library
- See example in [`web/index.php`](web/index.php)
- Run there server with `php -S 127.0.0.1:8080`
- Test:
    ```bash
    curl http://127.0.0.1:8080 \
        --data '{"id":"1", "method":"your.service:greet", "params":{"person":{"name":"bob","age":19}},"jsonrpc":"2.0"}'
    ```

## What's next?
- See [default controller](src/Controller/DefaultController.php) if you need multiple JSON-RPC endpoints.
- See [tests](test/Endpoint/EndpointTest.php) to know how it works.

## Inside
- Your request JSON string goes to [endpoint](src/Endpoint/Endpoint.php) which handles it and returns Symfony response.
- Endpoint chooses which [request passer](src/Request/Passer/RequestPasserInterface.php) can pass JSON string to request handler.
    - There are [single](src/Request/Passer/SingleRequestPasser.php) for `{}` and [batch](src/Request/Passer/BatchRequestPasser.php) for `[{},{}]` passers enabled by default. 
- Request passer also deserializes JSON to object and validates it.
- Request passer passes request object to [request handler](src/Request/Handler/RequestHandlerInterface.php) that pushes request somewhere to get response.
    - Now [service handler](src/Request/Handler/ServiceHandler.php) is used to:
        - Deserialize request `params` if set with [parameter converter](src/Request/Handler/RequestHandlerInterface.php).
        - Call public method of service with these params. **Services are restricted with [`ServiceContext`](src/Endpoint/Context/ServiceContext.php)**. For default controller see `zored.speech.controller.context` parameter.
        - Wait for `array` or [`AbstractResponse`](src/Response/Entity/AbstractResponse.php).
- Request then passed back and serialized to array.

## Events 
You can see available events [here](src/Event/EventNameInterface.php).
