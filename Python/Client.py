from PHPC import Client
import json
print('Connecting...')
Client = Client('http://localhost/php-c/PHP-C', True, 'root', 'admin')
PostData = {}
while True:
    INPUT = input('> ')
    if INPUT == 'add':
        Key = input('Key: ')
        Value = input('Value: ')
        PostData[Key] = Value
    if INPUT == 'print':
        print(' --- ')
        for i in PostData:
            print(i + ' => ' + PostData[i])
        print(' --- ')
    if INPUT == 'clear':
        PostData = {}
    if INPUT == 'send':
        Response = Client.sendRequest(PostData)
        print('Status: ' + str(Response.StatusCode))
        print('Message: ' + str(Response.Message))
        print('Data: ' + json.dumps(Response.Data))