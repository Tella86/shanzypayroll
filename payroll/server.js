// Import express
const express = require('express');
const app = express();
const port = 3000;

// Load environment variables from .env file
require('dotenv').config();

// Access Facebook App Secret and Access Token securely from environment variables
const FACEBOOK_APP_SECRET = process.env.FACEBOOK_APP_SECRET;
const ACCESS_TOKEN = process.env.ACCESS_TOKEN;

// Endpoint to retrieve access token (implement security as needed)
app.get('/get-access-token', (req, res) => {
  // For better security, add authentication or IP whitelisting here
  res.send(ACCESS_TOKEN);
});

// Serve the index.html file
app.get('/', (req, res) => {
  res.sendFile(__dirname + '/facebook.html');
});

// Start the server
app.listen(port, () => {
  console.log(`Server started on port ${port}`);
});
