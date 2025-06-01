const EventSession = require('../models/EventSession');

async function generateEventSessionId() {
    const lastEventSession = await EventSession.findOne({
        order: [['id', 'DESC']]
    });

    let newIdEventSession = 1;
    if (lastEventSession) {
        const lastIdEventSession = parseInt(lastEventSession.id.split('-')[1]);
        newIdEventSession = lastIdEventSession + 1;
    }

    return `ESE-${newIdEventSession.toString().padStart(3, '0')}`;
}

module.exports = generateEventSessionId;