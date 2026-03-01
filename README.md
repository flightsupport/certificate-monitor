# Certificate Monitor

A simple application to monitor SSL certificates and uptime for specified URLs.

## 🛠 Setup with Docker

Follow these steps to set up and run the application using Docker.

### 1️⃣ Create a `compose.yaml` file

Create a `compose.yaml` file in your project directory and add the following content:

```yaml
services:
  app:
    build:
      context: .
      dockerfile: ./Dockerfile
      target: production
    command: ["php", "artisan", "octane:start", "--server=frankenphp", "--port=8080"]
    environment:
      - APP_ENV=production
      - DB_CONNECTION=sqlite
      - DB_DATABASE=/var/www/html/database/database.sqlite
    volumes:
      - ./database:/var/www/html/database
      - ./import.json:/app/import.json
    ports:
      - 8080:8080
```

#### Config env variables 

Add these to the `compose.yaml` file.

| Key         | Value |
|-------------|-------|
| MAIL_MAILER | smtp  |
| MAIL_HOST   |       |
| MAIL_PORT   |       |
| MAIL_USERNAME |       |
| MAIL_PASSWORD |       |
| MAIL_EHLO_DOMAIN |       |
| MAIL_FROM_ADDRESS |       |
| MAIL_FROM_NAME |       |
| UPTIME_MONITOR_EMAIL | where to send notifications |
| UPTIME_MONITOR_EXPIRE_DAYS | default:10 |

The environment variables `MAIL_USERNAME` and `MAIL_PASSWORD` can be set as Docker Swarm secrets.
Just delete the env variables and set the secrets.
The `entryfile.sh` loads the secrets.


### 2️⃣ Create an `import.json` file

Create an `import.json` file in the same directory with the following content:

```json
[
    {
        "url": "https://localhost:8000",
        "uptime_check_enabled": false,
        "certificate_check_enabled": true
    }
]
```

### 3️⃣ Run the container

Start the application using Docker Compose:

```sh
docker compose up -d
```

This will set up and run the necessary services in the background.

Visit `http://localhost:8080/register` to get started.

## 📝 Configuration

- The app service handles certificate and uptime monitoring using FrankenPHP with Laravel Octane on port `8080`.
- The database is stored in an SQLite file, persisted via a volume mount at `./database`.

## 📌 Notes

- Ensure Docker and Docker Compose are installed on your system.
- Modify `import.json` to add more URLs for monitoring.
- Logs can be checked using:

  ```sh
  docker compose logs -f
  ```

## 🚀 Enjoy Monitoring!
