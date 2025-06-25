const { DataTypes } = require('sequelize');
const sequelize = require('../db');
const User = require('./User');
const Event = require('./Event');
const EventSession = require('./EventSession');

const Registration = sequelize.define('registration', {
    id: {
        type: DataTypes.STRING(15),
        primaryKey: true,
        allowNull: false
    },
    payment_status: {
        type: DataTypes.INTEGER,
        allowNull: false
    },
    payment_proof: {
        type: DataTypes.STRING(100),
        allowNull: true
    },
    qrcode: {
        type: DataTypes.STRING(100),
        allowNull: true
    },
    event_session_id: {
        type: DataTypes.STRING(15),
        allowNull: false
    },
    user_id: {
        type: DataTypes.STRING(15),
        allowNull: false
    }
}, {
    tableName: 'registration',
    timestamps: false,
    indexes: [{
        unique: true,
        fields: ['user_id', 'event_session_id']
    }]
});

Registration.belongsTo(User, { foreignKey: 'user_id' });
Registration.belongsTo(EventSession, { foreignKey: 'event_session_id' });

User.belongsToMany(EventSession, {
    through: Registration,
    foreignKey: 'user_id',
    otherKey: 'event_session_id',
});

Event.belongsToMany(User, {
    through: Registration,
    foreignKey: 'event_session_id',
    otherKey: 'user_id',
});

module.exports = Registration;