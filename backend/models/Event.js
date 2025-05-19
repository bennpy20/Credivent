const { DataTypes } = require('sequelize');
const sequelize = require('../db');
const Committee = require('./Committee');
const Speaker = require('./Speaker');

const Event = sequelize.define('event', {
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
    location: {
        type: DataTypes.STRING(200),
        allowNull: false,
    },
    poster_link: {
        type: DataTypes.STRING(100),
        allowNull: false,
    },
    max_participant: {
        type: DataTypes.INTEGER,
        allowNull: false,
    },
    transaction_fee: {
        type: DataTypes.INTEGER,
        allowNull: false,
    },
    event_status: {
        type: DataTypes.INTEGER,
        allowNull: false,
    },
    committee_id: {
        type: DataTypes.STRING(15),
        allowNull: false,
        references: {
            model: 'committee',
            key: 'id',
        },
        onUpdate: 'CASCADE',
        onDelete: 'RESTRICT'
    },
    speaker_id: {
        type: DataTypes.STRING(15),
        allowNull: false,
        references: {
            model: 'speaker',
            key: 'id',
        },
        onUpdate: 'CASCADE',
        onDelete: 'RESTRICT'
    },
    formatted_id: {
        type: DataTypes.VIRTUAL,
        get() {
            return `E-${this.id.toString().padStart(3, '0')}`;
        }
    }
}, {
    tableName: 'event',
    timestamps: true,
});

Event.belongsTo(Committee, { foreignKey: 'committee_id' });
Event.belongsTo(Speaker, { foreignKey: 'speaker_id' });

module.exports = Event;