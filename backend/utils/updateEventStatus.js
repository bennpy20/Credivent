const Event = require('../models/Event');
const EventSession = require('../models/EventSession');
const { Op } = require('sequelize');
const moment = require('moment-timezone');

async function updateEventStatus() {
    const now = moment.tz('Asia/Bangkok');

    const events = await Event.findAll();

    for (const event of events) {
        // Ambil semua sesi yang punya event_id = event.id
        const sessions = await EventSession.findAll({
            where: { event_id: event.id }
        });

        if (!sessions || sessions.length === 0) continue;

        const starts = sessions.map(s => moment(s.session_start));
        const ends = sessions.map(s => moment(s.session_end));

        const earliestStart = moment.min(starts);
        const latestEnd = moment.max(ends);

        let newStatus = 1;

        if (now.isBetween(earliestStart, latestEnd)) {
            newStatus = 2;
        } else if (now.isAfter(latestEnd)) {
            newStatus = 3;
        }

        // Update hanya jika status berubah
        if (event.event_status !== newStatus) {
            await event.update({ event_status: newStatus });
        }
    }
}

module.exports = updateEventStatus;