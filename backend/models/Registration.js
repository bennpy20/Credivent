const { DataTypes } = require('sequelize');
const sequelize = require('../db');
const User = require('./User');
const Event = require('./Event');

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
    event_id: {
        type: DataTypes.STRING(15),
        allowNull: false
    },
    user_id: {
        type: DataTypes.STRING(15),
        allowNull: false
    },
    formatted_id: {
        type: DataTypes.VIRTUAL,
        get() {
            return `REG-${this.id.toString().padStart(3, '0')}`;
        }
    }
}, {
    tableName: 'registration',
    timestamps: false,
    indexes: [{
        unique: true,
        fields: ['user_id', 'event_id']
    }]
});

Registration.belongsTo(User, { foreignKey: 'user_id' });
Registration.belongsTo(Event, { foreignKey: 'event_id' });

User.belongsToMany(Event, {
    through: Registration,
    foreignKey: 'user_id',
    otherKey: 'event_id',
});

Event.belongsToMany(User, {
    through: Registration,
    foreignKey: 'event_id',
    otherKey: 'user_id',
});

module.exports = Registration;