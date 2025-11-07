# Library Membership Registration Form

A responsive and modern Library Membership Registration System built using HTML, CSS, JavaScript, and PHP.  
This project allows users to register as library members and automatically receive a digital membership card via email using EmailJS.

---

## Features

- Registration form that collects personal, contact, and membership details  
- Stores user data in a CSV file (`registrations.csv`)  
- Generates a personalized digital membership card  
- Automatically emails the card to the registered user using EmailJS  
- Clean and modern user interface built with custom CSS  
- Responsive design that works on both desktop and mobile devices  

---

## Tech Stack

| Layer | Technology Used |
|:------|:----------------|
| Frontend | HTML5, CSS3, JavaScript |
| Backend | PHP |
| Database | CSV file (lightweight storage) |
| Email Service | EmailJS |
| Library | html2canvas (for capturing the membership card) |

---

## Project Structure

LibraryMembership-RegistrationForm/
│
├── index.html # Main registration form
├── display.php # Displays membership card after form submission
├── script.js # Handles form logic and EmailJS integration
├── style.css # Stylesheet for design
├── registrations.csv # Stores member data
└── README.md # Project documentation


---

## Setup Instructions

### 1. Clone the Repository
```bash
git clone https://github.com/<your-username>/LibraryMembership-RegistrationForm.git
cd LibraryMembership-RegistrationForm
2. Set Up PHP Server

Use XAMPP, WAMP, or any PHP-supported local server.

Place this folder inside:
C:/xampp/htdocs/
Start Apache in XAMPP and open the project in your browser:
http://localhost/LibraryMembership-RegistrationForm/
Future Enhancements

Store data in a MySQL database instead of CSV

Add an admin dashboard for managing members

Add QR code to membership cards for easy verification

Migrate from EmailJS to PHPMailer for production-level reliability
License

This project is licensed under the MIT License.
You are free to use, modify, and distribute it with proper credit.

“Begin your journey with thousands of books, resources, and a vibrant community.”

---

Would you like me to include a **diagram section** in this README that shows the flow (user fills form → PHP saves → JS sends email via EmailJS → user receives card)?  
It helps make the project look even more complete on GitHub.
