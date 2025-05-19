const { DataTypes } = require('sequelize');
const sequelize = require('../db');
const Member = require('./Member');
const Event = require('./Event');

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
    qrcode: {
        type: DataTypes.STRING(100),
        allowNull: true,
    },
    certificate_link: {
        type: DataTypes.STRING(100),
        allowNull: true,
    },
    member_id: {
        type: DataTypes.STRING(15),
        allowNull: false,
        references: {
            model: 'member',
            key: 'id',
        },
        onUpdate: 'CASCADE',
        onDelete: 'RESTRICT'
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
            return `A-${this.id.toString().padStart(3, '0')}`;
        }
    }
}, {
    tableName: 'attendance',
    timestamps: true,
});

Payment.belongsTo(Member, { foreignKey: 'member_id' });
Payment.belongsTo(Event, { foreignKey: 'event_id' });

module.exports = Attendance;