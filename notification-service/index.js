const express = require('express');
const bodyParser = require('body-parser');
const cors = require('cors');
const winston = require('winston');

const app = express();
const port = 3000;

app.use(bodyParser.json());
app.use(cors());

const logger = winston.createLogger({
  level: 'info',
  format: winston.format.combine(
    winston.format.timestamp(),
    winston.format.printf(({ timestamp, level, message }) => {
      return `[${timestamp}] ${level.toUpperCase()}: ${message}`;
    })
  ),
  transports: [
    new winston.transports.Console(),
    new winston.transports.File({ filename: 'notifications.log' })
  ],
});


app.post('/notify', (req, res) => {
  const { message, type } = req.body;

  if (!message) {
    return res.status(400).json({ status: 'error', message: 'Missing coach or message' });
  }

  // Log of the notification with type
  const notificationType = type ? type.toUpperCase() : 'GENERAL';
  logger.info(`[${notificationType}] Notification: ${message}`);

  res.status(200).json({ status: 'success' });
});


app.listen(port, () => {
  console.log(`Notification service running on port ${port}`);
});
