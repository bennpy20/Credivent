const Member = require('./Member');
const Event = require('./Event');
const EventParticipant = require('./EventParticipant');

EventParticipant.belongsTo(Member, { foreignKey: 'member_id' });
EventParticipant.belongsTo(Event, { foreignKey: 'event_id' });

Event.belongsToMany(Member, {
    through: EventParticipant,
    foreignKey: 'event_id',
    otherKey: 'member_id',
});

Member.belongsToMany(Event, {
    through: EventParticipant,
    foreignKey: 'member_id',
    otherKey: 'event_id',
});
