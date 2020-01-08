<p align="center"><img src="https://laravel.com/assets/img/components/logo-laravel.svg"> & <b> Angular </b></p>

#Project Summary:

I have created a laravel project for backend APIs and an angular SPA for admin panel.

Laravel Project Support several APIs that i am use in angular project for following tasks:

1. Login / Logout (API for register exists but no user interface)
2. Hotel Details Fetch and Hotel details edit
3. Rooms CRUD operations
4. Room Types CRUD operations
5. Room Prices CRUD operations
6 Bookings CRUD operations.

Similarly Angular Project provide functionality for all of the above mentioned CRUDs with following additional items:

1. A dashboard for quick summary
2. Login / Logout User interface
3. Bookings Calendar View
4. Search operations in all modules
5. API token Validation

---------------------------------------------------------------------------------------

#Technical Notes:-

You can run the API project by following steps:
go to directory from CMD.
######run "Composer install"
######run "php artisan serve"

I have added migrations for all required tables and created seeders for seeding them.
You can use following commands to get the database ready:

(please edit .env first to set database connection, currently i have named the database "laravel_hotel")

######php artisan migrate
######php artisan db:seed


I have not used any third party like JWT for API authentication as it was not a requirement.
Only a simple/basic api token validation is done.


After these steps our API is ready to use now come to our angular SPA:-

angular project is located in 
######(project_root)/public/angular_spa

goto angular project directory from CMD and run

######npm i 
(to install node modules) and
######"ng serve" 
to run locally or use my compiled  version from dist folder to run on a server.

please don't forget to adjust domain path of API in angular project currently it is set to 
#####"http://localhost:8000/".

To change the path go to 
#####(angular_root)/src/app/shared/services/http-client.service.ts

--------------------------------------------------------------------------------------------

Angular SPA Directory structure:

I have used modular approach in this project every big section will have its own angular module so we can lazy load it and enhance the speed.

So in (angular_root)/src/app you will see a folder named shared it includes all things which are being shared in all over the angular project like:

HttpClientService (For API)
CommonService for doing common tasks
AuthGuard to check for login status
etc

there is one more directory "layout" or module it have every thing of admin panel like dashboard and all CRUDs.

then another directory/module frontend currently it is not being used as we only have admin panel.

A login directory/module for handling login stuff.

Hope it helps you understand project.
