# LARAVEL ADMIN GENERATOR (OPTIMIZER)
This README file is still being written!

This is a project that intends to decrease in a significant amount the development of administrative panels using the PHP framework, Laravel, in its version 5.2 ...

Obviously not everything was developed from scratch, I put together a lot of plugins and libraries that I use in my administrative panels, I put them in this project and I developed ways to use them in an easier and faster way ...

I would like to make it clear here that the project is still being developed, there may be bugs, errors, but of course, if you notice something and want to modify it, correct it, add it, just do your odifications and do the pull request! If you want me to, leave it an issue!

Some libraries I've used in this project:

  - AdminLTE Template - https://adminlte.io
  - InfyOmLabs - https://github.com/InfyOmLabs/laravel-generator
  - Yajra Datatables - https://github.com/yajra/laravel-datatables
  - Slack Laravel - https://github.com/maknz/slack-laravel
  - More references will be added ...

# TECHNOLOGIES USED 
  
 In this project I used the technologies:
  - Laravel 5.2
  - Javascript (w/ jQuery)(in ES6)(compiled to vanilla JS)
  - Shellscript
  - Bootstrap
 

# REQUIREMENTS 
  The project requirements are:
    - PHP >= 5.5.9
    - minimum-stability: dev

# INSTALLATION
 To install this project is very simple, just follow the following steps:
   - Clone this project (git clone ...)
   - Access the directory and execute a composer update
   - Create a .env file (If the .env do not exists)(Thats very important!)
   - And is this!
   
```sh
git clone https://github.com/thiagopaiva99/laravel-admin-generator project-name
cd project-name
composer update | sudo composer update
touch .env 
``` 
   
So, lets start...
Eu deixei um seed para vocês rodarem, esse seed vai criar um usuario administrador apenas para logar, após vocês podem excluir ou manter ele, enfim, para rodar o seed, mas primeiro, execute o migrate para criar as tabelas:

```sh
php artisan migrate
php artisan db:seed
php artisan db:seed --class=DatabaseSeeder
```

Now we can login!

Now, everything we have here at the beginning is an ugly login screen, and do not worry, inside the panel will be even worse for now, but let's go !!

With the login done we will have a blank panel, zeroed, with nothing ... BUT ... we have some secret routes (that for lack of seed are secret hehehe), they are:
  - /admin/home -> Here we have our panel home
  - /admin/menus -> Here we are going to create and order the menus
  - /admin/options -> Here we are going to configure some options for our application
  - /admin/pages -> Here we are going to create pages (yes PAGES!!!!) for our application
  - /admin/holder -> Here we are going to customize our holder application (If it be setted on options hehe)
  - /admin/users -> The users CRUD
   


# THE MENUS CRUD
  Nessa parte de menus nós podemos criar menus, editar menus, deletar menus, ordenar menus, ou seja, um CRUD completo podemos dizer, com funcoes de pesquisa, exportação, paginação, ordenamento pela tabela, enfim, tudo que o Datatables oferece :D... A estrutura básica de um menu seria:
  - Menu: the menu title
  - Link: To where the menu will lead (consider putting it without / admin, the view already does this)
  - Icon: The icon that the menu will have in the side menu (example: fa fa-bars)
  - Active: When the menu will be active, because when it is active it gains a highlight (example: menus / * = will always be active in the menu route)
  - Menu Root: If it is a submenu, you can choose the parent of the menu here (it is still being implemented)
  - Appear To: In this project, users can have different levels, so the level you place here specifies to which level of users this menu will appear (Levels can be specified in the User model)
  
IMAGES HERE PLEASE

# THE OPTIONS CRUD
The options part is very simple but very great, we have a list of options (which will be shown below) and we only need to register the ones we want in our project, that within the application, some changes will occur, for example, we register the option Color_primary and the primary color of our entire project will be changed to this color we insert... Let me show the options list:
(It will also have a flap with buttons that perform some artisan commands)

| OPTION | WHAT IT CHANGES |
| ------ | ------ |
| color_primary | Set the primary color of the application |
| color_secondary| Set the secondary color of the application |
| company | Set the company name on footer |
| company_url | Set the company website link on footer |
| mini_title | Set the navbar title when the sidebar is minified |
| sidebar_search | Make the search field visible in the menu (already searching menus) |
| sidebar_user | Make visible the user frame in the sidebar |
| site_title | the title that the site will have in the browser |
| title | Set the name of the project itself |
| analytics | Expect as parameter the analytics ID to automatically implement the same |
|pixel | Wait as the facebook pixel ID parameter to automatically implement the same |
| holder | It tells if the panel will have a temporary holder (if true the holder options below will be used) |
| holder_title | The title that will not holder |
| holder_phrase | A sentence to use in the holder |
| holder_color | The color that will be used in the holder |
| holder_contact | Contact email of holder |
| holder_image | Image to be used of logo on holder |
| slack | Make possible communication via Slack with many panel actions |
| slack_enpoint | Group endpoint URL in Slack |
| slack_username | Name that the bot will take on Slack |
| slack_group | Group in which the bot will send the messages (general is the default) |
| new_relic | Allows you to add new relic monitoring in the project (in development) |
| robots | Enables customized robots.txt (under development) |
| facebook_login | Allows login to the panel by Facebook (in development) |
| github_login | Allows login to the panel by Github (in development) |
| twitter_login | Allows login to the panel by Twitter (in development) |
| google_login | Allows login to the panel by Google (in development) |
| send_email_home | Enables an email submission form on the dashboard's home page (under development) |
| todo_list | Enables a list of TODO's in home (in development) |
| can_register | Allows you to make registrations in the panel registration screen (in development) | 
| can_recovery | Allows to recover passwords in the panel (in development) |
| language | Permite trocar a linguagem de acordo com o charset passado (em desenvolvimento) |
| placeholder_loadings | Adds placeholders to page loads (in development) |
| dev_admin | Email of the dev that is developing, for diverse contacts (in development) |
| custom_error_view | Whenever an error occurs, it renders view and an email and is sent to dev_admin with a series of information (in development) |

PLEASE IMAGES HEREEEEEEE

# THE HOLDER PAGE

The holder page will only be if you create the holder option, so the holder can be accessed in the application, in this view, however, there are only the fields to set the contents of the holder!

# THE PAGES PAGE
In this part, we created pages (!!!!!!!!!), that is, we give the page a name (we can call the "object" page) ...
After giving the name to the page, we are adding the fields that this page will have, which will be described as follows:
  - field_name (1st) -> It is the name that the field will assume
  - dbType (2nd) -> It is the type of data that it will assume in the database
  - type (3rd) -> It's the type of field it will take on HTML
  - validations (4th) -> These are the validations that the field will have, both in the HTML and in the database
  - searchable (5th) -> Defines whether the field will be searchable in the CRUD table
  
  You inform all the data and then click on "Generate Page", so the page will be generated and all you have to do is to the php artisan serves (yes, it's necessary for now, I'm working on it) and run the php artisan Migrate, the files will be generated:

  - Model
  - Controller
  - CreateRequest
  - UpdateRequest
  - Views (table, create, update, delete, show, search)
  - DataTable Controller
  - DataTable Scope
  - Repository
  - Criteria
  - Table in the database
  - Migration for futures changes
  

# THE USERS CRUD
The CRUD of users was generated when we ran the first migrate, as we already have the user table I left CRUD users ready !!! Enjoy!!!