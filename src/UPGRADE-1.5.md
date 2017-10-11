- Added a new method to HandlerConfigInterface:
  `setFormSubmitProcessor(callable $callback)`. While technically a BC break,
  this interface is not designed to be implemented with a custom configuration.