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
            return `SES-${this.id.toString().padStart(3, '0')}`;
        }
    }
}, {
    tableName: 'speaker',
    timestamps: false,
});

Speaker.belongsTo(Event, { foreignKey: 'event_id' });

module.exports = Speaker;