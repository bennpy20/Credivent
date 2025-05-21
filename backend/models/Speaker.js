const { DataTypes } = require('sequelize');
const sequelize = require('../db');
const Event = require('./Event');

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
            return `SPK-${this.id.toString().padStart(3, '0')}`;
        }
    }
}, {
    tableName: 'speaker',
    timestamps: false,
});

Speaker.belongsTo(Event, { foreignKey: 'event_id' });

module.exports = Speaker;