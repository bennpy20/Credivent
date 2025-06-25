const { DataTypes } = require('sequelize');
const sequelize = require('../db');
const Registration = require('./Registration');

const Attendance = sequelize.define('attendance', {
    id: {
        type: DataTypes.STRING(15),
        primaryKey: true,
        autoIncrement: false,
        allowNull: false,
    },
    validity: {
        type: DataTypes.INTEGER,
        allowNull: false,
    },
    certificate_link: {
        type: DataTypes.STRING(100),
        allowNull: true,
    },
    registration_id: {
        type: DataTypes.STRING(15),
        allowNull: false,
        references: {
            model: 'registration',
            key: 'id',
        },
        onUpdate: 'CASCADE',
        onDelete: 'RESTRICT'
    }
}, {
    tableName: 'attendance',
    timestamps: false,
});

Attendance.belongsTo(Registration, { foreignKey: 'registration_id' });

module.exports = Attendance;