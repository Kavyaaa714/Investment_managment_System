# 💼 InvestMate - Investment Management Dashboard

**InvestMate** is a robust web-based platform built using PHP and MySQL that empowers users to manage their financial assets including stocks, properties, and fixed deposits. With a sleek dashboard, real-time data summary, and intuitive UI, InvestMate is your go-to tool for tracking and organizing personal investments.

---

## 🚀 Features

- **🔐 User Authentication**
  - Secure user registration and login system with email and password.
  
- **📊 Investment Overview Dashboard**
  - Displays total portfolio value.
  - Separate summary of Fixed Deposits, Properties, and Stocks.
  - Dynamic charts for investment distribution.

- **📈 Stocks Management**
  - Add, edit, delete stock investments.
  - Track number of shares, purchase price, and company name.

- **🏠 Property Tracker**
  - Manage properties with value, location, and purchase details.

- **🏦 Fixed Deposit Records**
  - Record fixed deposit details including bank, amount, and maturity.

- **💡 Light/Dark Mode**
  - Toggle theme for better viewing experience.

- **📱 Responsive Design**
  - Built with Bootstrap 5 for mobile and desktop compatibility.

---

## 🛠️ Tech Stack

- **Frontend:** HTML5, CSS3, Bootstrap 5
- **Backend:** PHP (Procedural)
- **Database:** MySQL / MariaDB
- **Tools:** Chart.js, XAMPP / LAMP

---

## 🗃️ Database Schema

Includes 4 tables:

- `users` — authentication
- `stocks` — stock investments
- `properties` — real estate assets
- `fixed_deposits` — fixed term deposits

👉 Database name should be: `investment_system`  
👉 Import file: `create_investment_tables.sql`

---

## 🚦 Getting Started

1. Clone or download this repo.
2. Create a database named `investment_system`.
3. Import the `create_investment_tables.sql` file into your MySQL server.
4. Update DB credentials in `config.php`.
5. Start XAMPP or your PHP server and open `index.php`.

---

## 📎 License

This project is open-source and free to use for educational or personal portfolio use.

---

## 🤝 Contributing

Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.
