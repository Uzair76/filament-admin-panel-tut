<h2 id="introduction--setup-filamentphp">✨ Concepts Covered</h2>

1. [Setup FilamentPHP](#setup-filamentphp)  
2. [Customize FilamentPHP Theme](#how-to-customize-filamentphp-theme)  
3. [Building E-commerce Migrations & Models](#building-e-commerce-migrations--models)  
4. [Building Resources in FilamentPHP](#building-resources-in-filamentphp)  
5. [Resource Modifiers & Filters in FilamentPHP](#resource-modifiers--filters-in-filamentphp)  
6. [Actions in FilamentPHP](#actions-in-filamentphp)  
7. [Building Customer, Order & Category Resources](#building-customer-order--category-resources)  
8. [Setting Up Global Search in FilamentPHP](#setting-up-global-search-in-filamentphp)  
9. [Customizing the Navbar in FilamentPHP](#customizing-the-navbar-in-filamentphp)  
10. [Defining Relationships in FilamentPHP](#defining-relationships-in-filamentphp)  
11. [Creating Dashboards with Widgets, Charts & Tables](#creating-dashboards-with-widgets-charts--tables)  
12. [Configuring Plugins in FilamentPHP](#configuring-plugins-in-filamentphp)  

---

<h2>🚀 Simple Deploy Script</h2>

Use the following commands to deploy the project efficiently:

```bash
git pull
cp .env.example .env
php artisan key:generate
composer install --no-dev --optimize-autoloader
php artisan optimize
php artisan route:cache
php artisan cache:clear
php artisan migrate
