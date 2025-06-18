const Event = require('../models/Event');
const EventSession = require('../models/EventSession');
// const { Op } = require('sequelize');
// const moment = require('moment-timezone');

async function updateEventStatus() {
    const nowUTC = new Date();
    const now = new Date(nowUTC.getTime() + (7 * 60 * 60 * 1000));

    const events = await Event.findAll();

    for (const event of events) {
        const sessions = await EventSession.findAll({
            where: { event_id: event.id }
        });

        if (!sessions || sessions.length === 0) continue;

        const starts = sessions.map(s => new Date(s.session_start));
        const ends = sessions.map(s => new Date(s.session_end));

        const earliestStart = new Date(Math.min(...starts.map(d => d.getTime())));
        const latestEnd = new Date(Math.max(...ends.map(d => d.getTime())));

        let newStatus = 1;

        if (now >= earliestStart && now <= latestEnd) {
            newStatus = 2;
        }
        else if (now > latestEnd) {
            newStatus = 3;
        }

        if (event.event_status !== newStatus) {
            await event.update({ event_status: newStatus });
        }
    }
}

module.exports = updateEventStatus;