const { DataTypes } = require('sequelize');
const sequelize = require('../db');
const FinanceTeam = require('./FinanceTeam');
const Member = require('./Member');
const Event = require('./Event');

const Payment = sequelize.define('payment', {
    id: {
        type: DataTypes.STRING(15),
        primaryKey: true,
        autoIncrement: false,
        allowNull: false,
    },
    price: {
        type: DataTypes.INTEGER,
        allowNull: false,
    },
    payment_status: {
        type: DataTypes.INTEGER,
        allowNull: false,
    },
    payment_proof: {
        type: DataTypes.STRING(100),
        allowNull: false,
    },
    finance_team_id: {
        type: DataTypes.STRING(15),
        allowNull: false,
        references: {
            model: 'finance_team',
            key: 'id',
        },
        onUpdate: 'CASCADE',
        onDelete: 'RESTRICT'
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
            return `P-${this.id.toString().padStart(3, '0')}`;
        }
    }
}, {
    tableName: 'payment',
    timestamps: true,
});

Payment.belongsTo(FinanceTeam, { foreignKey: 'finance_team_id' });
Payment.belongsTo(Member, { foreignKey: 'member_id' });
Payment.belongsTo(Event, { foreignKey: 'event_id' });

module.exports = Payment;