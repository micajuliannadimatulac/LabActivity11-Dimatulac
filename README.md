
# Barangay Bulletin - Purok Dimatulac

A Laravel-based barangay bulletin application for sharing
community announcements, viewing author profiles, and
participating in discussions.

## Developer

Mica Julianna Dimatulac
BS Computer Science
Ateneo de Davao University

## Technologies

- Laravel and PHP
- SQLite
- Eloquent ORM
- Blade Templates
- Tailwind CSS
- Vite
- JavaScript
- Laravel Form Requests

## Features

### Public Bulletin
- Display published announcements
- Live title search while typing
- Filter by category and tag name
- Pinned announcements appear first
- Nine announcements per page
- Display authors, tags, and comment counts
- View complete announcements
- Increment announcement view counts
- Hide unpublished drafts
- Return HTTP 404 for missing or unpublished posts

### User Accounts
- Session-based login and logout
- Guest-only login page
- Authenticated comment submission
- Author-only announcement creation
- Published and draft announcements

### Relationships
- User hasOne Author
- User hasMany Comments
- Author belongsTo User
- Author hasMany Posts
- Author hasManyThrough Comments via Posts
- Post belongsTo Author
- Post hasMany Comments
- Post belongsToMany Tags
- Comment belongsTo User
- Comment belongsTo Post
- Tag belongsToMany Posts

### Eager Loading
The application uses with(), load(), and withCount()
to retrieve relationships efficiently.

Lazy loading prevention is enabled during development
to help identify N+1 query problems.

### Form Requests
StoreCommentRequest validates comment submissions.
StorePostRequest validates announcement creation.

Both requests include authorization rules.

## Personalization

Barangay: Purok Dimatulac

Kagawad: Mica Dimatulac

Birthday announcement date: August 7, 2026

Pinned announcement:
Happy Birthday, Mica Dimatulac!

Categories:
- Announcement
- Health
- Peace & Order
- Ayuda
- Events

Tags:
- Purok Dimatulac
- Urgent
- Libre
- Schedule
- Community

Pagination: 9 posts per page.

## Seeded Test Accounts

Kagawad account:
Email: kagawad.dimatulac@bulletin.test
Password: bulletin2026

Resident account:
Email: lola.dimatulac@bulletin.test
Password: bulletin2026

These are development-only credentials.

## Sample Data

Fresh seeding generates:
- 5 authors
- 40 published factory posts
- 10 unpublished drafts
- 1 pinned birthday announcement
- 10 users
- 5 tags
- Random comments on published posts
- At least 3 comments on the pinned announcement

Total seeded posts: 51

## Database Structure

The application includes these main tables:

- users
- authors
- posts
- comments
- tags
- post_tag

The post_tag table implements a many-to-many
relationship with a composite primary key.

Foreign keys maintain referential integrity.
Comments are linked to posts and registered users.

## Installation

1. Clone the repository:

   git clone https://github.com/micajuliannadimatulac/LabActivity11-Dimatulac.git

2. Open the project:

   cd LabActivity11-Dimatulac

3. Install PHP dependencies:

   composer install

4. Install frontend dependencies:

   npm install

5. Create the environment file:

   Copy-Item .env.example .env

6. Generate the application key:

   php artisan key:generate

7. Configure SQLite in .env:

   DB_CONNECTION=sqlite

8. Create the SQLite database file if necessary:

   New-Item database/database.sqlite -ItemType File

9. Create the database tables and seed sample records:

   php artisan migrate:fresh --seed

10. Start the frontend development server:

    npm run dev

11. Open another terminal and start Laravel:

    php artisan serve

12. Visit:

    http://127.0.0.1:8000/posts

Note: migrate:fresh deletes existing database tables.
Use it only with a disposable or backed-up database.

## Testing

Check registered routes:

    php artisan route:list

Compile Blade templates:

    php artisan view:cache

Run automated tests:

    php artisan test

Open Tinker:

    php artisan tinker

Example relationship verification:

    $post = App\Models\Post::with(['author', 'tags', 'comments.user'])->first();

    $post->relationLoaded('author');

    $post->relationLoaded('tags');

    $author = App\Models\Author::first();

    get_class($author->comments());

Expected relationship type:
Illuminate\Database\Eloquent\Relations\HasManyThrough

## Git Ignore

The repository excludes:
- .env
- vendor/
- node_modules/
- database/*.sqlite
- database/*.sqlite-*
- generated build files

The SQLite database is recreated locally using
Laravel migrations and seeders.

## Repository

https://github.com/micajuliannadimatulac/LabActivity11-Dimatulac
