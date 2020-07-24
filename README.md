# Simple Blog in Symfony
# Functionalities 
## Views
 - The blog post list
 - The detail view of the blog post
 - The app is able to accept comments for blog posts
   - Comments are anonymous, no need for users but you can provide details
   - Comments can be given asynchronously
   - The comment is appeared immediately after posting (using js)
 - Admin capabilities [(with the Symfony security bundle)]
   - Can add/edit title of blog posts
   - Can add/edit content of blog posts
   - Can delete/hide comments
 - Search function, so that the user can search in blog posts (both title and content)
 
## Technology Used
  Symfony Framework 5.1, Php 7.2.5 or higher, MySQL, Bootstrap 
    
## Installation Guide
  To run the application some softwares need to be installed. Here, the installation process is provided for linux Operating System(Ubuntu).
### Software needed
 -  PHP 7.2.5 or higher
 ``` 
 sudo add-apt-repository -y ppa:ondrej/php
 sudo apt-get update
 sudo apt-get install php7.2 php7.2-cli php7.2-common
 sudo apt-get install php7.2-curl php7.2-gd php7.2-json php7.2-mbstring php7.2-intl php7.2-mysql php7.2-xml php7.2-zip
 ```
 - Composer
 ``` 
 sudo php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
 sudo php -r "if (hash_file('sha384', 'composer-setup.php') === 'e5325b19b381bfd88ce90a5ddb7823406b2a38cff6bb704b0acc289a09c8128d4a8ce2bbafcd1fcbdc38666422fe2806') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
 sudo php composer-setup.php --install-dir=/usr/local/bin --filename=composer
 sudo php -r "unlink('composer-setup.php');"
```
 - Symfony CLI
 ``` 
 # https://symfony.com/download
 wget https://get.symfony.com/cli/installer -O - | bash 
 cd
 sudo mv .symfony/bin/symfony /usr/local/bin/symfony
```
 - MySQL
 ```  
 # I'm using xampp, it is shipped with MySQL with some other softwares
 wget https://www.apachefriends.org/xampp-files/7.2.32/xampp-linux-x64-7.2.32-0-installer.run
 sudo ./xampp-linux-x64-7.2.32-0-installer.run
 # To start the mysql server
 sudo /opt/lampp/lampp startmysql
 ```

### Running the application
 - Change the `.env` file's db user name and db user password according to the MySQL database setup. Change `mysql://root:@127.0.0.1:3306/simpleblog?serverVersion=5.7` to `mysql://db_usere_name:db_user_password@127.0.0.1:3306/simpleblog?serverVersion=5.7`
 - Install the necessary packages
``` 
composer install
```
 - Install an editor for writing the blog description in prettier format
```
php bin/console ckeditor:install
php bin/console assets:install public/
```
 - Create a database and related schema
```
php bin/console doctrine:database:create
php bin/console doctrine:schema:update --force
```
 - Run the application
```
symfony serve
```

## Misc
 - Few front end libraries are added for better user experience
 - For registering/login Symfony security bundle added
 - Ckeditor bundle added in Symfony for adding/editing post in prettier way. 