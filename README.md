```
#!yaml

Feather: User service
Scenario: Registration by phone number (4 h.)
		Given not auth user
		And input phone number
		When input valid phone number
			Then generate new jwt token
			Then save new User 
			Then emit EventSendSms
			Then return new User
		When phone number is invalid
			Then throw ValidationException
	Scenario: Authorize user by validation code from SMS (4 h.)
		Given auth user by jwt token
		And input sms code
		When sms code is valid
			Then generate new jwt token 
			Then activate User
			Then return saved User
		When sms code is not valid 
			Then throw ValidationException
	Scenarion: Registration by e-mail (4 h.)
		Given auth user by jwt token
		And input e-mail
		When e-mail is valid
			Then generate new jwt token
			Then save new User 
			Then add event SendEmailEvent to message queue
			Then return new User
		When e-mail is invalid
			Then throw ValidationException
	Scenario: Save user settings (3 h.)
		Given auth user by jwt token 
		And input full name
		When settings is valid
			Then update user
			Then return saved user
			Then add event SendUserEvent to message queue
		When settings is invalid
			Then throw ValidationException
	Scenario: Send user to 1C server (6 h.)
		Given catch event SendUserServer
		When send User to server is successful
			Then delete event from message queue
		When send User to server is failed
			Then throw SendUserException
	Scenario: Set water consumption (4 h.)
		Given auth user by jwt token
		And input water consumption
		When consumption is valid
			Then send water consumption to 1C
			Then update User
			Then return saved User
		When consumption is invalid
			Then throw ValidationException
	Scenario: Load user balance from 1C Server(4 h.)
		Given catch event GetUserBalanceEvent
		When get user balance is done
			Then delete event from message queue
		When get user balance is failed
			throw new LoadUserBalanceException
	Scenario: Send push when user blance change (2 h.) 
		Given catch event LoadCountOfBottleEvent
		When new count of bottle differ exists count of bottle
			Then emit SendPushEvent
	Scenario: Load count of users water bottle from 1c Server(4 h.) 
		Given catch event LoadCountOfUserBottleEvent
		When get count of users is success
			Then delete event from message queue
	Scenario: Load calculated average daily water consumption from 1C Server (4 h.)
		Given catch event LoadAverageEvent
		When get calculated average is done
			Then delete event from message queue
		When get calculated average is failed
			Then throw LoadAverageException
	Scenario: Set count of users water bottle (5 h.)
		Given auth user by jwt token
		And input count of botttle
		When count of botttle is valid
			Then send count of botttle to 1C
			Then update User
			Then return saved User
		When consumption is invalid
			Then throw ValidationException
	Scenario: Load count of users water bottle from 1C Server (4 h.)
		Given catch event LoadCountOfBottleEvent
		When get count is done
			Then delete event from message queue
		When get count is failed
			Then throw LoadCountOfBottleException

Feather: SMS service
	Scenario: Send SMS (см. http://sms.ru/api/send) (4 h.)
	    Given catch event SendSmsEvent
	    When sms send is successfull
	    	Then delete event from message queue
	    When sms send is failed 
	    	Then throw SendSmsException

Feather: E-Mail service
	Scenario: Send E-Mail (4 h.)
		Given catch event SendEmailEvent
		When e-mail send is successfull
	    	Then delete event from message queue
	    When e-mail send is failed 
	    	Then throw SendEmailException	    	

Feather: Adress service	    
	Scenario: Save the adress (6 h.)
		Given auth user by jwt token
		And input location
		When location is valid
			Then parse location get city, street and number of home
			Then save id of user, city, street and number of home
			Then return saved Adress
		When location is invalid
			Then throw ValidationException

Feather: 1C server status service
	I every 5 minutes send http request to server

	Scenario: Send ping to 1C server (2 h.)
		Given response after ping 1c server 
		When return 200 status
			Then save status Active
		When return not 200 status
			Then save status Error

	Scenario: Get server status (4 h.)
		When status is Active
			Then return True
		When status is Error
			Then return False

Feather: Products service
	Scenario: Load products from 1C Server (6 h.)
		Given catch event LoadProductsEvent
		When load products is done
			Then save array of Product
			Then delete event from message queue
		When load products is failed
			Then throw error LoadProductsException

	Scenario: Get products (2 h.)
		Given auth user by jwt token
		When return array of Product from database
			Then return array

Feather: Products link service
	Scenario: Load link products from 1C Server (5 h.)
		Given catch event LoadLinkProductsEvent
		When load link products is done
			Then save array of LinkProducts
			Then delete event from message queue
		When load link products is failed
			Then throw LoadLinkProductsException
	Scenario: Get products link (2 h.)
		Given auth user by jwt token
		When return array of ProductLink from database
			Then return array

Feather: Push service

	Scenario save push settings (2 h.)
		Given auth user by jwt token
		And input id of User
		And input push token
		And input device ID
		And input operation system(ios\android)
		When settings is valid
			Then save or update PushSettings
			Then return saved PushSettings
		When settings is invalid
			Then throw ValidationException

	Scenario: Send push to mobile phone (4 h.)
	    Given catch event SendPushEvent
	    When push send is successfull
	    	Then delete event from message queue
	    When push send is failed 
	    	Then throw SendPushException

Feather: Users adress service
	Scenario: Save UserAdress (3 h.) 
		Given auth user by jwt token
		And input id of User
		And input id of Adress
		When link is valid
			Then save UserAdress
		When link is invalid
			Then throw ValidationException
	Scenario: Get adresses by User (2 h.)
		Given auth user by jwt token
		And input id of User
		When return array of UserAdress from database
			Then return array

Feather: Order adress service
	Scenario: Save UserAdress (3 h.) 
		Given auth user by jwt token
		And input id of Adress
		When link is valid
			Then save UserAdress
		When link is invalid
			Then throw ValidationException
	Scenario: Get adresses by Order (2 h.)
		Given auth user by jwt token
		And input id of Order
		When User is not creator Order
			Then throw AccessDeniedException
		When return array of UserAdress from database
			Then return array

Feather: Order service
	Scenario: save order (8 h.) 
		Given auth user
		And input id of Adress
		And input payment method
		And input Time of delivery
		When order is valid
			Then save Order
			Then return saved Order
		When order is invalid
			Then throw ValidationException
	Scenario: load order from 1C Server (8 h.) 
		Given catch event LoadOrderEvent
		When save order is done
			Then delete event from message queue
		When save order is failed
			Then throw LoadOrderException

	
Feather: News Service
	Scenario: load news from 1C Server (2 h.)
		Given catch event LoadNewsEvent
			Then save array of News
			Then delete event from message queue 
	Scenario: get news (2 h.)
		Given any user
		When return array of News from database
			Then return array
```