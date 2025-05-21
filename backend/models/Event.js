const { DataTypes } = require('sequelize');
const sequelize = require('../db');
const User = require('./User');

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
    start_date: {
        type: DataTypes.DATEONLY,
        allowNull: false,
    },
    end_date: {
        type: DataTypes.DATEONLY,
        allowNull: false,
    },
    user_id: {
        type: DataTypes.STRING(15),
        allowNull: false,
        references: {
            model: 'user',
            key: 'id',
        },
        onUpdate: 'CASCADE',
        onDelete: 'RESTRICT'
    },
    formatted_id: {
        type: DataTypes.VIRTUAL,
        get() {
            return `EVN-${this.id.toString().padStart(3, '0')}`;
        }
    }
}, {
    tableName: 'event',
    timestamps: true,
});

Event.belongsTo(User, { foreignKey: 'user_id' });

module.exports = Event;