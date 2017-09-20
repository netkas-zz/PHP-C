# PHP-C
PHP-C Is a Lightweight PHP Class allowing lazy programmers to build applications to communicate with their server using HTTP/HTTPS -> PHP

  - The ability to run PHP-C Classes as Scripts
  - Support for .NET Applications
  - Support for Python 3.+


### Example PHP-C Script

You can write your own scripts that can be executed by PHP-C
File Name: Example.php
```php
<?PHP
	class CScript{
		private $Object;
		public function __construct($Object){
			$this->Object = $Object;
		}
		public function Execute(){
		    $Name = $this->Object->_DATA['name'];
			$this->Object->endSession(100, array('msg'=>"Hello, $Name !"));
		}
	}
?>
```

And to execute it (python)...

```python
from PHPC import Client
import json

# Connect to the server first
Client = Client('http://localhost/php-c/PHP-C', True, 'root', 'admin')

# The first item tells PHP-C to hook a script
# The second item defines the script to execute (without .php extension)
# The third and so on item passes as arguments to the executing script
Data = {'svrhook': 'hook', 'script': 'Example', 'example': 'John'}
# The final command would be 'example.php -example John'

# Send the Request
Response = Client.sendRequest(Data)

print('Status: ' + str(Response.StatusCode))
print('Message: ' + str(Response.Message))
print('Data: ' + json.dumps(Response.Data))

print(' Example Script Output ')
print(Response.Data['msg']) # Read the response property 'msg' (Example.php)
# Output -> 'Hello, John !'
```

# Security Notice!
This library is not supposed to be used as a production element. Rather for quick commands to your server as a web developer/system administrator. if used as a public service this can lead to security issues.