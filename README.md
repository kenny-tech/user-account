To test the endpoints

Clone the project and run composer install. Then run php artisan serve on your terminal.



NOTE:

Make sure your Postman app Headers is set to accept json request. Please set it this way:

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
  
