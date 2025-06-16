# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Architecture

This is a WordPress plugin for creating custom post types and taxonomies. The plugin follows:

- **PSR-4 autoloading** with namespace `Custom_PTT\` mapped to `includes/`
- **Dependency injection** using PHP-DI container managed by `Container_Singleton`
- **Registerable interface** pattern for services (`Infrastructure/Registerable.php`)
- **WordPress VIP coding standards** with performance optimization and object caching
- **Strict typing** with `declare(strict_types=1)` in all PHP files

### Core Components

- **Plugin.php** - Main plugin class that bootstraps services via DI container
- **Container_Singleton.php** - Singleton pattern for dependency injection container
- **Post_Type/Post_Type.php** - Handles custom post type registration with caching
- **Taxonomy/Taxonomy.php** - Handles custom taxonomy registration with caching
- **Admin/** - WordPress admin interface with form handlers and list tables
- **Utilities.php** - Helper functions and utilities

### Key Features

- Object caching for performance (cache groups: `custom_ptt_post_types`, `custom_ptt_taxonomies`)
- WordPress hooks for extensibility: `custom_ptt_taxonomy_args`, `custom_ptt_post_type_args`
- VIP-compatible with comprehensive error handling and logging

## Development Commands

### PHP Development
```bash
# Install dependencies
composer install

# Code standards check
composer phpcs

# Auto-fix code standards
composer phpcbf

# Run unit tests
composer unit

# Generate test coverage
composer coverage
```

### Frontend Development (TailwindCSS)
```bash
# Install Node.js dependencies
npm install

# Watch mode for CSS development
npm run dev

# Build production CSS
npm run build
```

### WordPress Environment (wp-env)
```bash
# Start local development environment
npm run start-env

# Stop environment
npm run stop-env

# Install composer dependencies in wp-env
npm run composer-install

# Run PHPUnit tests in wp-env
npm run test-php
```

## Code Standards

- Follow **WordPress-VIP-Go** coding standards via PHPCS
- Maintain **PHP 8.0+** compatibility
- Use **strict typing** (`declare(strict_types=1)`)
- Implement **object caching** for performance-critical operations
- Follow **WordPress coding conventions** for file naming and structure

## Testing

- Unit tests located in `tests/unit/` using PHPUnit 9.6+
- Bootstrap file: `tests/unit/bootstrap.php`
- Coverage reports generated to `coverage/html/`
- Tests follow WordPress VIP testing standards

### Testing Philosophy

**Follow Test-Driven Development (TDD) - Red, Green, Refactor:**
1. **Red** - Write a failing test that defines desired behavior
2. **Green** - Write minimal code to make the test pass
3. **Refactor** - Improve code while keeping tests green

**DRY Principle for Tests:**
- Treat test code as production code - apply same quality standards
- Create reusable test helpers and data providers
- Extract common setup/teardown logic into base classes
- Use factory methods for test data creation
- Share test utilities across test classes

**Test Quality Guidelines:**
- Each test should verify one specific behavior
- Use descriptive test method names that explain the scenario
- Follow AAA pattern: Arrange, Act, Assert
- Mock external dependencies to ensure isolation
- Test both happy path and edge cases

**Test Analysis Process:**
Before modifying code to make tests pass:
1. **Understand** - Read the test name, comments, and assertions
2. **Question** - Does the test make sense? What behavior is it testing?
3. **Review** - Is this the intended behavior for the application?
4. **Implement** - Only then make code changes to match expected behavior