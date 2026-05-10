# SocialNet Project

## Introduction
This is a "Social Network" web application project developed as part of a web application mock assignment. The system allows administrators to manage users and enables users to interact through profiles and a friendship system.

## Tech Stack
The application is built using the following technologies:
* **Language:** PHP
* **Database:** MySQL
* **Web Server:** Nginx
* **Operating System:** Linux

## Database Specifications
The application uses a database named **"socialnet"**. It consists of the following tables:

### 1. `account` table
This table stores user credentials and profile information:
* `Id`: Primary key (Auto-increment).
* `username`: Unique identifier for login.
* `fullname`: User's full name.
* `password`: Hashed password for security.
* `description`: Content for the user's Profile Page.

### 2. `friendships` table (Extended Feature)
This table manages relationships between users:
* `username1`: The initiator of the relationship.
* `username2`: The target of the relationship.
* `status`: Current state (e.g., 'sent', 'received', 'friend').

## Application Pages & Navigation
The project implements the following URLs and functionalities:

* **Admin Page (`/admin/newuser.php`):** Used to create/add new user accounts to the system.
* **SignIn Page (`/socialnet/signin.php`):** Authenticates users and redirects them to the Home Page upon success.
* **Home Page (`/socialnet/index.php`):** Displays logged-in user information and a list of other users in the system to view their profiles.
* **Setting Page (`/socialnet/setting.php`):** Allows users to edit their "Profile Page content" (stored in the `description` column).
* **Profile Page (`/socialnet/profile.php`):** Displays user information. It accepts a query string `?owner=username`. If no query string exists, it defaults to the logged-in user.
* **About Page (`/socialnet/about.php`):** A static page containing the developer's information.
* **SignOut Page (`/socialnet/signout.php`):** Resets session data and redirects to the Home or SignIn page.

## Extended Features
Beyond the basic requirements, this version includes:
1.  **Friendship System:** Users can send friend requests to "strangers" from the Home Page or Profile Page.
2.  **Request Management:** Separate sections on the Home Page for "Friends", "Pending Requests (Received)", and "Friend Suggestions".
3.  **Acceptance Logic:** Users can accept incoming friend requests, which updates the status to 'friend' for both parties using a symmetrical data entry trick.

## Installation & Setup
1.  Clone this repository to your Linux environment.
2.  Import the provided `db.sql` file into your MySQL server to create the database and required tables.
3.  Configure your Nginx server to point to the project directory.
4.  Update `db_connect.php` with your local database credentials (host, username, password).

## Author
* **Student Name:** Vu Tien Tue
* **Student Number:** [Điền mã số sinh viên của bạn vào đây]
* **University:** Hanoi University of Science and Technology (HUST)
