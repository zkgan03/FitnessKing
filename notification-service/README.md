# Notification Service

**Author: Dong Wei Jie**

This project is a simple Notification Service built using Node.js and Express, with additional libraries like `body-parser`, `cors`, and `winston` for handling requests, enabling CORS, and logging.

> **Note**: This is a Node.js project that serves as a RESTful notification service.

## Introduction

This notification service is designed to handle notifications sent to the specified endpoint. It is built using Express and offers a basic RESTful API to manage notifications efficiently.

## Technologies Used

- Node.js
- Express
- body-parser
- cors
- winston

## Setup Guide

### Prerequisites

- Ensure you have [Node.js](https://nodejs.org/en/) installed (version 14 or later is recommended).

### Installation

1. Clone the repository and navigate to the project directory:

   ```bash
   git clone <repository-url>
   cd notification-service

2. Initialize the project and install the required dependencies:

   ```bash
   npm init -y
   npm install express body-parser cors winston

3. Make sure the notification service URL is in the .env file located in the ./core directory:

   ```bash
   NOTIFICATION_SERVICE_URL=http://localhost:3000/notify

4. Start the server for development:

   ```bash
   node index.js

5. Make sure both URLs are accessible in your browser:

   ```bash
   Main Application: http://127.0.0.1:8000
   Notification Service: http://localhost:3000 

