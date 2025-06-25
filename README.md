# 📔 MotiMate – Motivational Journal

**MotiMate** is a simple web application that allows users to track their mood, productivity, and personal goals. The project encourages personal growth, self-reflection, and daily motivation.

This app was developed as a final year school project and serves as a practical tool for building better habits and staying motivated.

---

## ✨ Features

- 🧠 Daily mood and productivity logging
- 🎯 Goal management – create, edit, complete
- 📊 Dashboard with personal statistics
- 💬 Random motivational quotes
- 👤 User registration and login
- 🔐 Secure password hashing
- 📅 History of logs and goal status

---

## 🚀 Getting Started

### 1. Clone the repository
```bash
git clone https://github.com/Balestruci0o/MotiMate.git
cd MotiMate/rocnikovka_pit
```

### 2. Import the database
* Open a tool like phpMyAdmin or MySQL CLI

* Create a new database named motimate

* Import the SQL file:
```bash
sql/motimate.sql
```

### 3. Configure the database connection
* In the file config/db.php, set your local database credentials:

```php
$host = 'localhost';
$dbname = 'motimate';
$username = 'root';
$password = '';
```

### 4. Run the application
* Launch the project in your browser using a local web server (e.g., XAMPP or Laragon):

---

## 🛠️ Built With

* <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/html5/html5-original.svg" alt="HTML5" width="20" /> **HTML5**
* <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/css3/css3-original.svg" alt="CSS3" width="20" /> **CSS3**
* <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/php/php-original.svg" alt="PHP" width="20" /> **PHP**
* <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/mysql/mysql-original.svg" alt="MySQL" width="20" /> **MySQLi** 

---

## 📁 Project Structure
```bash
📦 rocnikovka_pit
 ┣ 📂 config
 ┃ ┗ 📄 db.php
 ┣ 📂 includes
 ┃ ┣ 📄 header.php
 ┃ ┣ 📄 footer.php
 ┃ ┗ 📄 functions.php
 ┣ 📂 public
 ┃ ┣ 📄 index.php
 ┃ ┣ 📄 login.php / register.php / logout.php
 ┃ ┣ 📄 dashboard.php / stats.php / quotes.php
 ┃ ┣ 📄 add_goal.php / edit_goal.php / view_goal.php / toggle_goal.php
 ┃ ┣ 📄 goals.php
 ┃ ┗ 📄 daily_log.php
 ┣ 📂 sql
 ┃ ┗ 📄 motimate.sql
```

---

## 🔐 User Features

| Feature             | Description                            |
| ------------------- | -------------------------------------- |
| **Registration**    | Create a new account                   |
| **Login**           | Authenticate with email and password   |
| **Daily Log**       | Rate your daily mood and productivity  |
| **Goal Management** | Add, edit, and mark goals as completed |
| **Quotes**          | View random motivational quotes        |
| **Stats**           | View summarized activity and progress  |

---

## 🎯 Project Goals

* Practice working with PHP and MySQLi

* Build a meaningful and functional personal app

* Learn to handle forms, validation, and sessions

* Apply secure data handling practices

---

## 📄 License

* This project is licensed under the MIT License.
* Feel free to use, modify, and share it.
