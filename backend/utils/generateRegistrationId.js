const Registration = require('../models/Registration');

async function generateRegistrationId() {
    const lastRegistration = await Registration.findOne({
        order: [['id', 'DESC']]
    });

    let newIdRegistration = 1;
    if (lastRegistration) {
        const lastIdRegistration = parseInt(lastRegistration.id.split('-')[1]);
        newIdRegistration = lastIdRegistration + 1;
    }

    return `REG-${newIdRegistration.toString().padStart(3, '0')}`;
}

module.exports = generateRegistrationId;