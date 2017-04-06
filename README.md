# JSON-RPC Symfony Bundle
Bundle for JSON-RPC 2.0.

## Features
- Validation. Based on [Symfony Validator component](https://github.com/symfony/validator).
- Deserialization Based on [JMS](https://github.com/schmittjoh/serializer).
- Customization. Change your rules for **[JSON-RPC 2.0](http://json-rpc.org/wiki/specification)**.

## How?
- Add `new Zored\JsonRpcBundle\ZoredJsonRpcBundle()` to `app/AppKernel.php`.
- [See example](example/YourBundle).
    - Handle request with `lamoda.json_rpc.endpoint` in your controller (or use existing one: `@ZoredJsonRpcBundle/Controller/DefaultController.php`) 
    - 
    
## Inside
- Your response JSON string goes to *endpoint* which handles it and returns Symfony response.
- Endpoint chooses which *request passer* can pass JSON string to request handler.
    - There are *single* for `{}` and *batch* for `[{},{}]` passers enabled by default. 
- Request passer also deserializes JSON to object and validates it.
- It sends request object to **request handler** that pushes request somewhere to get response.
    - Now [`ServiceHandler`](Zored/JsonRpcBundle/Request/Handler/ServiceHandler.php) is used to:
        - Deserialize request `params` if set with *parameter converter*.
        - Call public method of service with these params. **Services are restricted with `$context['serviceNames']`**.
        - Wait for `array` or [`AbstractResponse`](Zored/JsonRpcBundle/Response/Entity/AbstractResponse.php).
- Request then passed back and serialized to array.

## Events 
You can see available events [here](Zored/JsonRpcBundle/Event/EventNameInterface.php).