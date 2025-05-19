const { DataTypes } = require('sequelize');
const sequelize = require('../db');
const User = require('./User');

const Committee = sequelize.define('committee', {
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
            return `C-${this.id.toString().padStart(3, '0')}`;
        }
    }
}, {
    tableName: 'committee',
    timestamps: false,
});

Committee.belongsTo(User, { foreignKey: 'user_id' });

module.exports = Committee;