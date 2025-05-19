require('dotenv').config();

const express = require('express');
const app = express();
const routes = require('./routes/route');
const bodyParser = require('body-parser');
const cors = require('cors');

app.use(cors());
app.use(bodyParser.json());
app.use('/api', routes);

app.listen(3000, () => {
    console.log('Node.js API running on http://localhost:3000');
});