const Speaker = require('../models/Speaker');

async function generateSpeakerId() {
    const lastSpeaker = await Speaker.findOne({
        order: [['id', 'DESC']]
    });

    let newIdSpeaker = 1;
    if (lastSpeaker) {
        const lastIdSpeaker = parseInt(lastSpeaker.id.split('-')[1]);
        newIdSpeaker = lastIdSpeaker + 1;
    }

    return `SPK-${newIdSpeaker.toString().padStart(3, '0')}`;
}

module.exports = generateSpeakerId;