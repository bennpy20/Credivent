const { DataTypes } = require('sequelize');
const sequelize = require('../db');
const Event = require('./Event');

const EventSession = sequelize.define('event_session', {
    id: {
        type: DataTypes.STRING(15),
        primaryKey: true,
        autoIncrement: false,
        allowNull: false,
    },
    session: {
        type: DataTypes.INTEGER,
        allowNull: true,
    },
    title: {
        type: DataTypes.STRING(100),
        allowNull: true,
    },
    session_start: {
        type: DataTypes.DATE,
        allowNull: false,
    },
    session_end: {
        type: DataTypes.DATE,
        allowNull: false,
    },
    description: {
        type: DataTypes.STRING(250),
        allowNull: false,
    },
    event_id: {
        type: DataTypes.STRING(15),
        allowNull: false,
        references: {
            model: 'event',
            key: 'id',
        },
        onUpdate: 'CASCADE',
        onDelete: 'RESTRICT'
    },
    formatted_id: {
        type: DataTypes.VIRTUAL,
        get() {
            return `ESE-${this.id.toString().padStart(3, '0')}`;
        }
    }
}, {
    tableName: 'event_session',
    timestamps: false,
});

EventSession.belongsTo(Event, { foreignKey: 'event_id' });

module.exports = EventSession;