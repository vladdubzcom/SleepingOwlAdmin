# SleepingOwl Admin

## Project Overview

SleepingOwl Admin is an administrative interface builder for the Laravel framework. It provides a quick and easy way to create full-featured, customized administration panels for Laravel applications. This project is a composer package (`vladdubzcom/sleepingowladmin`, originally `laravelrus/sleepingowl`) designed to be integrated into existing Laravel projects.

**Key Technologies & Architecture:**
*   **Backend:** PHP (>= 8.1), Laravel Framework (^v10.50).
*   **Frontend:** JavaScript/Vue.js (v2), Bootstrap 4, jQuery.
*   **Asset Management:** Laravel Mix is used to compile and bundle frontend assets (CSS, JS).
*   **Testing:** PHPUnit.

## Building and Running

Since this is a package rather than a standalone application, "running" it generally means integrating it into a host Laravel application. However, for package development, you'll need to install dependencies and compile assets.

**1. Install PHP Dependencies:**
```bash
composer install
```

**2. Install Frontend Dependencies:**
```bash
npm install
# or
yarn install
```

**3. Compile Frontend Assets:**
The project uses Laravel Mix. The following NPM scripts are available:
*   `npm run dev` (or `npm run development`): Compiles assets for development.
*   `npm run watch`: Compiles assets and watches for changes.
*   `npm run hot`: Hot Module Replacement (HMR) for development.
*   `npm run prod` (or `npm run production`): Compiles and minifies assets for production.

**4. Testing:**
The project uses PHPUnit for testing. You can run the test suite using:
```bash
./vendor/bin/phpunit
# or if you have a global installation:
phpunit
```

## Development Conventions

*   **Autoloading:** The package uses PSR-4 autoloading.
    *   The `SleepingOwl\Admin\` namespace is mapped to the `src/` directory.
    *   The `SleepingOwl\Tests\` namespace is mapped to the `tests/` directory.
*   **Code Style:** The project uses StyleCI, and there is a `.styleci.yml` file present, indicating that strict coding standards (likely PSR-12 or a similar standard) are enforced.
*   **Testing:** New features and bug fixes should be accompanied by appropriate PHPUnit tests in the `tests/` directory.
*   **Frontend Framework:** Note that the frontend currently relies heavily on Vue 2, jQuery, and Bootstrap 4. Any UI contributions should align with this stack.

===

<laravel-boost-guidelines>
=== foundation rules ===

# Laravel Boost Guidelines

The Laravel Boost guidelines are specifically curated by Laravel maintainers for this application. These guidelines should be followed closely to enhance the user's satisfaction building Laravel applications.

## Foundational Context
This application is a Laravel application and its main Laravel ecosystems package & versions are below. You are an expert with them all. Ensure you abide by these specific packages & versions.

- php - 8.5.4

## Conventions
- You must follow all existing code conventions used in this application. When creating or editing a file, check sibling files for the correct structure, approach, and naming.
- Use descriptive names for variables and methods. For example, `isRegisteredForDiscounts`, not `discount()`.
- Check for existing components to reuse before writing a new one.

## Verification Scripts
- Do not create verification scripts or tinker when tests cover that functionality and prove it works. Unit and feature tests are more important.

## Application Structure & Architecture
- Stick to existing directory structure; don't create new base folders without approval.
- Do not change the application's dependencies without approval.

## Frontend Bundling
- If the user doesn't see a frontend change reflected in the UI, it could mean they need to run `npm run build`, `npm run dev`, or `composer run dev`. Ask them.

## Replies
- Be concise in your explanations - focus on what's important rather than explaining obvious details.

## Documentation Files
- You must only create documentation files if explicitly requested by the user.

=== boost rules ===

## Laravel Boost
- Laravel Boost is an MCP server that comes with powerful tools designed specifically for this application. Use them.

## Artisan
- Use the `list-artisan-commands` tool when you need to call an Artisan command to double-check the available parameters.

## URLs
- Whenever you share a project URL with the user, you should use the `get-absolute-url` tool to ensure you're using the correct scheme, domain/IP, and port.

## Tinker / Debugging
- You should use the `tinker` tool when you need to execute PHP to debug code or query Eloquent models directly.
- Use the `database-query` tool when you only need to read from the database.

## Reading Browser Logs With the `browser-logs` Tool
- You can read browser logs, errors, and exceptions using the `browser-logs` tool from Boost.
- Only recent browser logs will be useful - ignore old logs.

## Searching Documentation (Critically Important)
- Boost comes with a powerful `search-docs` tool you should use before any other approaches when dealing with Laravel or Laravel ecosystem packages. This tool automatically passes a list of installed packages and their versions to the remote Boost API, so it returns only version-specific documentation for the user's circumstance. You should pass an array of packages to filter on if you know you need docs for particular packages.
- The `search-docs` tool is perfect for all Laravel-related packages, including Laravel, Inertia, Livewire, Filament, Tailwind, Pest, Nova, Nightwatch, etc.
- You must use this tool to search for Laravel ecosystem documentation before falling back to other approaches.
- Search the documentation before making code changes to ensure we are taking the correct approach.
- Use multiple, broad, simple, topic-based queries to start. For example: `['rate limiting', 'routing rate limiting', 'routing']`.
- Do not add package names to queries; package information is already shared. For example, use `test resource table`, not `filament 4 test resource table`.

### Available Search Syntax
- You can and should pass multiple queries at once. The most relevant results will be returned first.

1. Simple Word Searches with auto-stemming - query=authentication - finds 'authenticate' and 'auth'.
2. Multiple Words (AND Logic) - query=rate limit - finds knowledge containing both "rate" AND "limit".
3. Quoted Phrases (Exact Position) - query="infinite scroll" - words must be adjacent and in that order.
4. Mixed Queries - query=middleware "rate limit" - "middleware" AND exact phrase "rate limit".
5. Multiple Queries - queries=["authentication", "middleware"] - ANY of these terms.

=== php rules ===

## PHP

- Always use curly braces for control structures, even if it has one line.

### Constructors
- Use PHP 8 constructor property promotion in `__construct()`.
    - <code-snippet>public function __construct(public GitHub $github) { }</code-snippet>
- Do not allow empty `__construct()` methods with zero parameters unless the constructor is private.

### Type Declarations
- Always use explicit return type declarations for methods and functions.
- Use appropriate PHP type hints for method parameters.

<code-snippet name="Explicit Return Types and Method Params" lang="php">
protected function isAccessible(User $user, ?string $path = null): bool
{
    ...
}
</code-snippet>

## Comments
- Prefer PHPDoc blocks over inline comments. Never use comments within the code itself unless there is something very complex going on.

## PHPDoc Blocks
- Add useful array shape type definitions for arrays when appropriate.

## Enums
- Typically, keys in an Enum should be TitleCase. For example: `FavoritePerson`, `BestLake`, `Monthly`.
</laravel-boost-guidelines>
