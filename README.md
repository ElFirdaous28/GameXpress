# GameXpress Admin API

## Overview
This is the admin API for the GameXpress e-commerce platform, built using Laravel 11. The project is part of a backend development cycle spanning three weeks.

## API Documentation
The API documentation is available through Postman collections:

- **Authentication**: https://documenter.getpostman.com/view/42977502/2sAYkBt2L8
- **Users**: https://documenter.getpostman.com/view/42977502/2sAYkBt2Fn
- **Categories**: https://documenter.getpostman.com/view/42977502/2sAYkBt2L9
- **Products**: https://documenter.getpostman.com/view/42977502/2sAYkBt2LA
- **Dashboard**: https://documenter.getpostman.com/view/42977502/2sAYkBt2LB

These links provide detailed information on each endpoint, including request and response structures.

## Technologies Used
- **Framework**: Laravel 11 (PHP 8.3)
- **Authentication**: Laravel Sanctum
- **Role & Permission Management**: Spatie Permission
- **Testing**: Pest PHP & PHPUnit
- **Database**: MySQL

## Role & Permission System
- **Roles**: Super Admin, Product Manager, User Manager
- **Permissions**:  view_dashboard
                    view_products, create_products, edit_products, delete_products
                    view_categories, create_categories, edit_categories, delete_categories
                    view_users, create_users, edit_users, delete_users
