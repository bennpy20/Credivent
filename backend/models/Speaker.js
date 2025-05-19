const { DataTypes } = require('sequelize');
const sequelize = require('../db');

const Speaker = sequelize.define('speaker', {
    id: {
        type: DataTypes.STRING(15),
        primaryKey: true,
        autoIncrement: false,
        allowNull: false,
    },
    name: {
        type: DataTypes.STRING(150),
        allowNull: false,
    },
}, {
    tableName: 'speaker',
    timestamps: false,
});

module.exports = Speaker;