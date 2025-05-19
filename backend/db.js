const { Sequelize } = require('sequelize');

const sequelize = new Sequelize('credivent', 'root', '', {
    host: 'localhost',
    dialect: 'mysql',
});

module.exports = sequelize;