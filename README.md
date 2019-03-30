# Naughts And Crosses
This is implementation of Naughts And Crosses (Tic-tac-toe) game in PHP. 

## Description
Application allows to play on the board of any size and any length of winning line. Therefore, it is possible to have a simple game with 3x3 field and much larger size of board

## Design and implementation process
According to the clean architecture project assume splitting code into three layers: Domain (with business logic), Application and Infrastructure.
First is written Domain layer with unit test (TDD). Domain layer contain: Entities, Value Objects, Domain services etc.
After implementing Domain is written application layer witch contain: commands and handlers
At the end, the infrastructure layer is written. This layer contain code responsible for communicate with "outside world" (processing http request, storing in database, sending emails etc.) 

#### Dependency Rule
Code in the layer depends only on same or deeper layer. It's means that code in domain layer depends only on itself, code in application layer depends on itself and domain etc.  
 
#### AI implementation
Evaluation for the best move is based on minmax algorithm
    
## Running the tests
```
composer runAllTests
```     
     
## Dependencies
PHP7

## Run application as a built-in web server
php -S 127.0.0.1:8000 -t public

## API
### Start game [POST]
http://127.0.0.1:8000/game

with body:
``
{
	"boardSize" : 3,
	"lineSize": 3,
	"startingPlayer": 1
}
``
### Make move [PATCH]
http://127.0.0.1:8000/game/{id}/makeMove
with body:
``
{
	"x" : 0,
	"y" : 0
}
``
### Get game status [GET]
http://127.0.0.1:8000/game/{id}/status

### Make move by AI [PATCH]
http://127.0.0.1:8000/game/{id}/AIMove

## License
This project is licensed under the MIT License - see the LICENSE.md file for details

## Author
@swydmuch
