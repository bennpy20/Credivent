const Event = require('../models/Event');

async function generateEventId() {
    const lastEvent = await Event.findOne({
        order: [['id', 'DESC']]
    });

    let newIdEvent = 1;
    if (lastEvent) {
        const lastIdEvent = parseInt(lastEvent.id.split('-')[1]);
        newIdEvent = lastIdEvent + 1;
    }

    return `EVN-${newIdEvent.toString().padStart(3, '0')}`;
}

module.exports = generateEventId;