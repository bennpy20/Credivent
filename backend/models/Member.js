const { DataTypes } = require('sequelize');
const sequelize = require('../db');
const User = require('./User');

const Member = sequelize.define('member', {
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
    phone_number: {
        type: DataTypes.STRING(15),
        allowNull: false,
    },
    major: {
        type: DataTypes.STRING(50),
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
            return `M-${this.id.toString().padStart(3, '0')}`;
        }
    }
}, {
    tableName: 'member',
    timestamps: false,
});

Member.belongsTo(User, { foreignKey: 'user_id' });

module.exports = Member;