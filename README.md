
# Barangay Bulletin – Purok Dimatulac

A Laravel 12 web application for displaying barangay
announcements, health updates, community events,
peace and order notices, and assistance programs.

## Technologies Used

- Laravel 12
- PHP
- SQLite
- Blade Templates
- Tailwind CSS v4
- Vite
- Eloquent ORM
- Faker Factories and Seeders

## Features

- Display published barangay announcements
- Search announcements by title
- Filter announcements by category
- Show pinned announcements first
- Display 9 posts per page
- View complete announcement details
- Automatically increment post view counts
- Hide unpublished drafts
- Display a 404 page for invalid or unpublished posts
- Responsive layout using Tailwind CSS

## Database

The application uses two tables:

### Authors
- id
- name
- position
- email
- created_at
- updated_at

### Posts
- id
- author_id
- title
- category
- body
- views
- is_published
- is_pinned
- published_at
- created_at
- updated_at

The posts table references authors through author_id.
Deleting an author also deletes their posts.

## Sample Data

The database seeders generate:

- 5 authors
- 40 published factory posts
- 10 unpublished draft posts
- 1 personalized pinned announcement

Total: 51 posts

## Personalization

Barangay: Purok Dimatulac

Kagawad: Mica Dimatulac

Email: kagawad.dimatulac@bulletin.test

Pinned Post: Purok Dimatulac Clean-up Drive

Pinned Publication Date: August 7, 2026

Categories:
1. Announcement
2. Health
3. Peace & Order
4. Ayuda
5. Events

The Events category was selected to represent community
activities and barangay programs.

Pagination uses 9 posts per page, based on the
9 letters in "Dimatulac".

## Installation

1. Clone the repository:

   git clone https://github.com/micajuliannadimatulac/LabActivity10-Dimatulac.git

2. Open the project:

   cd LabActivity10-Dimatulac

3. Install PHP dependencies:

   composer install

4. Copy the environment configuration:

   cp .env.example .env

5. Generate the application key:

   php artisan key:generate

6. Set DB_CONNECTION=sqlite in the .env file.

7. Create database/database.sqlite if it does not exist.

8. Create the database tables and seed records:

   php artisan migrate:fresh --seed

9. Install frontend dependencies:

   npm install

10. Start the Vite development server:

    npm run dev

11. In another terminal, start Laravel:

    php artisan serve

12. Open:

    http://127.0.0.1:8000/posts

## Tinker CRUD Demonstration

The application demonstrates Eloquent operations
using Laravel Tinker.

Example:

    $post = App\Models\Post::find(1);
    $post->title = 'Purok Dimatulac: ' . $post->title;
    $post->save();

Evidence screenshot:

docs/tinker.png

## Developer

Mica Julianna Dimatulac

BS Computer Science
Ateneo de Davao University
