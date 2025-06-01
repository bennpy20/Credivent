const { DataTypes } = require('sequelize');
const sequelize = require('../db');
const EventSession = require('./EventSession');

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
    speaker_image: {
        type: DataTypes.STRING(100),
        allowNull: true,
    },
    event_session_id: {
        type: DataTypes.STRING(15),
        allowNull: false,
        references: {
            model: 'event_session',
            key: 'id',
        },
        onUpdate: 'CASCADE',
        onDelete: 'RESTRICT'
    },
    formatted_id: {
        type: DataTypes.VIRTUAL,
        get() {
            return `SPK-${this.id.toString().padStart(3, '0')}`;
        }
    }
}, {
    tableName: 'speaker',
    timestamps: false,
});

Speaker.belongsTo(EventSession, { foreignKey: 'event_session_id' });

module.exports = Speaker;