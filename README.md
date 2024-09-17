Hi, thank you for taking the time of reviewing this code.

The project contains the following features:

- Create task (info to save: task name, priority, timestamps)
- Edit task
- Delete task
- Reorder tasks with drag and drop in the browser. Priority should automatically be updated based on this. #1 priority
  goes at top, #2 next down and so on.
- Tasks should be saved to a mysql table.
- BONUS POINT: add project functionality to the tasks. User should be able to select a project from a dropdown and only
  view tasks associated with that project.

## How to run the code

1. ```git clone``` repo.
2. ```composer install```
3. configure db.
    1. Create ```.env``` file
    2. Fill information for database
4. ```php artisan db:seed``` You will enter 3 projects (Projects 1, Project 2, Project 3) to your db
