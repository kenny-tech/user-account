To test the endpoints

Clone the project and run composer install. Then run php artisan serve on your terminal.



NOTE:

Make sure your Postman app Headers is set to accept json request. i.e`:

"Accept": application/json



Endpoints 

# Register: [POST]
  - URL: http://localhost:8000/api/register
  - Params: name, email, password
  
  Sample Request Data
  {
    "name" : "Kenny",
    "email" : "kenny@gmail.com",
    "password": "kenny1234"
  }
  
  
# Login: [POST]
  - URL: http://localhost:8000/api/login
  - Params: email, password
  
  Sample Request Data
  {
    "email" : "kenny@gmail.com",
    "password": "kenny1234"
  }
  
  
  NOTE:
  
  After successful login, you will receive an access token. You would need to set this access token as a Bearer Token in the Authorization header.
  
  Your header should look like this.
  
  'headers' => [
    'Accept' => 'application/json',
    'Authorization' => 'Bearer '. $accessToken,
  ]
  
  # The endpoints below requires an access token.
  
  
  # Create Account: [POST]
  - URL: http://localhost:8000/api/account/create
  - Params: account_name, user_id
  
  Sample Request Data
  {
    "account_name" : "First Bank",
    "user_id" : 10
  }
 
 
  
 # Get Accounts: [GET]
  - URL: http://localhost:8000/api/account/getall



 # Fund Account: [POST]
  - URL: http://localhost:8000/api/account/fund
  - Params: account_number, amount, user_id, description (optional)
  
  Sample Request Data
  {
    "account_number" : 8428951422,
    "amount" : 500,
    "user_id" : 1,
    "description" : "testing"
  }
  
  
  # Transfer Fund: [POST]
  - URL: http://localhost:8000/api/account/transfer_fund
  - Params: account_number (account number you are transferring to), amount, user_id (sender user_id), description (optional)
  
  Sample Request Data
  {
    "account_number" : 8428951422,
    "amount" : 300,
    "user_id" : 1,
    "description" : "transferring fund to you"
  }
  
  
  # Withdraw Fund: [POST]
  - URL: http://localhost:8000/api/account/withdraw_fund
  - Params: account_number, amount, user_id, description (optional)
  
  Sample Request Data
  {
    "account_number" : 8428951422,
    "amount" : 1000,
    "user_id" : 1,
    "description" : "Withdrawing fund..."
  }
  
  
  
