# OtakuNote - The Series Tracker 🎬📚  

Track your reading and watching progress for manga, anime, dramas, and more!  
Built with **Laravel**, **Inertia.js**, and **Vue.js**, this open-source project is simple yet powerful.

## Libraries & Tools 🛠️  

Here are the main libraries and tools used in this project:

### Backend:
- **Laravel**: PHP framework for robust backend development.
- **Laravel Breeze**: Starter kit for authentication and frontend integration.
- **MySQL**: Database for storing all data.
- **Ziggy**: Enables using Laravel routes in JavaScript.

### Frontend:
- **Inertia.js**: Bridging Laravel with Vue for seamless SPA experience.
- **Vue.js 3**: Frontend library for building reactive user interfaces.
- **Vue Use**: Collection of essential Vue Composition API utilities.
- **Pinia**: State management library for Vue.js.
- **Headless UI**: Accessible, unstyled UI components for Vue.js.
- **Tailwind CSS**: Utility-first CSS framework for styling.
- **Axios**: Promise-based HTTP client for API requests.

### Others:
- **Composer**: PHP dependency manager.
- **Node.js & npm**: For managing JavaScript dependencies and building assets.
- **Vite**: Modern asset bundler for development and production.

## Features ✨  
- Manage types (e.g., Anime, Manga, Manhwa).  
- Track status (e.g., Ongoing, Completed).  
- Save series information and organize your library.  
- Keep notes on the last episode/chapter read or watched.  

---

## Installation 🛠️  

Follow these steps to get the project up and running locally:

### Requirements  
Ensure you have the following installed:  
- PHP >= 8.1  
- Composer  
- Node.js & npm  
- MySQL or any database of your choice

### Steps
1. **Clone the Repository**  
- Clone the project to your local machine:
    ```bash
    git clone https://github.com/dafinrmaulana/otakunote.git
- Go to directory
    ```bash
    cd otakunote

2. **Install dependencies**
- PHP dependencies
    ```bash
    composer install
- Javascript
    ```bash
    npm install
3. **Environtment Setup**
Configure the environment settings:
- Copy the `.env` example file:

    ```bash
    cp .env.example .env
- Update .env with your database and application configuration.
4. **Generate Application Key**
    ```bash
    php artisan key:generate
5. **Run Migration**
    ```bash
    php artisan migrate
6. **Build frontend assets**
- For development
    ```bash
    npm run dev
- For production
    ```bash
    npm run build
7. **Run the server**
    ```bash
    php artisan serve

The application will be accessible at http://localhost:8000.

## Contributing 🤝
Contributions are welcome! Feel free to submit a pull request or create an issue to report bugs or request features.

## License 📜
This project is open-source and available under the MIT License.

## Demo 🚀
Check out the live demo: [Link Demo](https://otakunote.devcores.my.id)

