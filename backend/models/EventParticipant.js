const { DataTypes } = require('sequelize');
const sequelize = require('../db');

const EventParticipant = sequelize.define('event_participant', {
    id: {
        type: DataTypes.STRING(15),
        primaryKey: true,
        allowNull: false
    },
    member_id: {
        type: DataTypes.STRING(15),
        allowNull: false
    },
    event_id: {
        type: DataTypes.STRING(15),
        allowNull: false
    },
    formatted_id: {
        type: DataTypes.VIRTUAL,
        get() {
            return `C-${this.id.toString().padStart(3, '0')}`;
        }
    }
}, {
    tableName: 'event_participant',
    timestamps: false
});

module.exports = EventParticipant;
