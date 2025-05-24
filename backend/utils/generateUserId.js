const User = require('../models/User');

async function generateUserId() {
    const lastUser = await User.findOne({
        order: [['id', 'DESC']]
    });

    let newIdUser = 1;
    if (lastUser) {
        const lastIdUser = parseInt(lastUser.id.split('-')[1]);
        newIdUser = lastIdUser + 1;
    }

    return `UID-${newIdUser.toString().padStart(3, '0')}`;
}

module.exports = generateUserId;