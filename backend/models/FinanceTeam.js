const { DataTypes } = require('sequelize');
const sequelize = require('../db');
const User = require('./User');

const FinanceTeam = sequelize.define('finance_team', {
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
            return `F-${this.id.toString().padStart(3, '0')}`;
        }
    }
}, {
    tableName: 'finance_team',
    timestamps: false,
});

FinanceTeam.belongsTo(User, { foreignKey: 'user_id' });

module.exports = FinanceTeam;